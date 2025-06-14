<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Landlord;
use App\Models\Announcement;
use App\Models\Bill;
use App\Models\MaintenanceRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        return match ($role) {
            'landlord' => $this->landlordDashboard($user->landlord),
            'tenant' => $this->tenantDashboard($user->tenant),
            default => abort(403),
        };
    }

    protected function tenantDashboard(Tenant $tenant)
    {
        // Eager load relationships for the tenant dashboard to minimize queries
        $tenant->load([
            'room.property',
            'bills' => function ($query) {
                $query->orderBy('due_date', 'desc'); // Order by due date for latest bill logic
            },
            'maintenanceRequests',
            'user'
        ]);

        // Get the latest unpaid bill for "Rent Due"
        // Ensure bills are sorted to reliably get the "latest" by due_date if needed
        $latestBill = $tenant->bills->where('status', 'unpaid')->first();

        // Calculate outstanding balance explicitly here
        $outstandingBalance = $tenant->bills->where('status', 'overdue')->sum('amount_due');

        // Get the latest overdue bill for "Outstanding Balance" footer
        $latestOverdueBill = $tenant->bills->where('status', 'overdue')->sortByDesc('due_date')->first();


        // Calculate months stayed
        $monthsStayed = 0;
        if ($tenant->move_in_date) {
            $monthsStayed = $tenant->move_in_date->diffInMonths(Carbon::now());
        }

        // Fetch relevant announcements for the tenant, limited and ordered
        // Using the scope `relevantToTenant`
        $announcements = Announcement::relevantToTenant($tenant)->latest()->take(5)->get();

        // Fetch maintenance requests for the tenant, limited and ordered
        $maintenanceRequests = MaintenanceRequest::where('tenant_id', $tenant->id)->latest()->take(5)->get();


        return view('tenant.dashboard', compact(
            'tenant',
            'latestBill',
            'outstandingBalance', // Pass the calculated outstanding balance
            'latestOverdueBill',
            'monthsStayed',
            'announcements',
            'maintenanceRequests'
        ));
    }

    protected function landlordDashboard(Landlord $landlord)
    {
        // Eager load necessary relationships for landlord dashboard calculations
        $landlord->load('properties.rooms.tenants.bills', 'properties.rooms.maintenanceRequests');

        $totalProperties = $landlord->properties->count();
        $totalOccupants = 0;
        $totalMonthlyRentDue = 0;
        $totalOutstandingRent = 0;
        $totalRentCollected = 0;

        foreach ($landlord->properties as $property) {
            foreach ($property->rooms as $room) {
                // Calculate total occupants
                $totalOccupants += $room->tenants->count();

                foreach ($room->tenants as $tenant) {
                    // Total Monthly Rent Due (sum of rent_amount for all occupied rooms)
                    // Assuming rent_amount from the room is the monthly rent due if tenant is present
                    if ($tenant->room_id === $room->id) { // Ensure tenant is actually in this room
                        $totalMonthlyRentDue += $room->rent_amount;
                    }

                    // Total Outstanding Rent (sum of outstanding balances for all tenants)
                    $totalOutstandingRent += $tenant->bills()->where('status', 'overdue')->sum('amount_due');

                    // Total Rent Collected (sum of paid bills for all tenants)
                    $totalRentCollected += $tenant->bills()->where('status', 'paid')->sum('amount_due');
                }
            }
        }

        // Fetch latest announcements relevant to the landlord (system, property, room)
        $landlordAnnouncements = Announcement::relevantToLandlord($landlord)->latest()->take(5)->get();

        // Fetch latest pending maintenance requests for the landlord's properties
        $landlordMaintenanceRequests = MaintenanceRequest::whereHas('room.property', function ($query) use ($landlord) {
            $query->where('landlord_id', $landlord->id);
        })->where('status', 'pending')->latest()->take(5)->get();


        return view('landlord.dashboard', compact(
            'landlord',
            'totalProperties',
            'totalOccupants',
            'totalMonthlyRentDue',
            'totalOutstandingRent',
            'totalRentCollected',
            'landlordAnnouncements',
            'landlordMaintenanceRequests'
        ));
    }
}
