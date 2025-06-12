<?php

namespace App\Http\Controllers;

use App\Enums\MaintenanceRequestStatus;
use App\Models\MaintenanceRequest;
use App\Models\Room;
use App\Models\Tenant;
use Illuminate\Http\Request;

class MaintenanceRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user(); // Get the authenticated user
        $role = $user->role;
        $info = $request->query('status'); // 'status' is the correct query parameter

        $requests = collect(); // Initialize an empty collection

        if ($role === 'landlord') {
            // Correctly access properties through the landlord relationship on the User model
            // Ensure the user has a landlord record before trying to access properties
            $propertyIds = $user->landlord ? $user->landlord->properties()->pluck('id') : collect();

            // Only query if landlord has properties
            if ($propertyIds->isNotEmpty()) {
                $query = MaintenanceRequest::whereIn('room_id', Room::whereIn('property_id', $propertyIds)->pluck('id'));

                if ($info && $info !== 'all') { // Filter by status if provided and not 'all'
                    $query->where('status', $info);
                }

                // Eager load tenant and their user data for displaying tenant names on cards
                $requests = $query->with('tenant.user')->latest()->get();
            } else {
                $requests = collect(); // No properties means no requests to show for landlord
            }

        } elseif ($role === 'tenant') {
            $tenant = $user->tenant;
            $query = MaintenanceRequest::where('tenant_id', $tenant->id);

            if ($info && $info !== 'all') { // Filter by status if provided and not 'all'
                $query->where('status', $info);
            }

            // Eager load tenant and their user data for consistency (though less critical for tenant's own requests)
            $requests = $query->with('tenant.user')->latest()->get();
        } else {
            abort(403, 'Unauthorized Access');
        }

        return match ($role) {
            'landlord' => view('landlord.request.index', [
                'requests' => $requests,
            ]),
            'tenant' => view('tenant.request.index', [
                'requests' => $requests,
            ]),
        };
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();

        if ($user->role === 'tenant') {
            $tenant = $user->tenant;

            return view('tenant.request.create', ['room' => $tenant->room]);
        }

        abort(403, 'Unauthorized Requester');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:50',
            'description' => 'required|string|max:3000',
        ]);

        $user = auth()->user();
        $tenant = $user->tenant;

        MaintenanceRequest::create([
            'tenant_id' => $tenant->id,
            'room_id' => $tenant->room_id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => MaintenanceRequestStatus::PENDING->value,
        ]);

        return redirect()->route('request.index')->with('toast.success', [
            'title' => 'Request Posted',
            'content' => 'Your request has been sent to your landlord.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        // Eager load room, tenant, and tenant's user data for comprehensive display
        $requestRecord = MaintenanceRequest::with(['room.property', 'tenant.user'])->findOrFail($id); // Added room.property eager loading

        if ($user->role === 'tenant' && $user->tenant->id !== $requestRecord->tenant_id) {
            abort(403, 'Unauthorized Access');
        }

        if ($user->role === 'landlord') {
            // Correctly access properties through the landlord relationship on the User model
            $propertyIds = $user->landlord ? $user->landlord->properties()->pluck('id') : collect();
            if ($propertyIds->isEmpty() || ! in_array($requestRecord->room->property_id, $propertyIds->toArray())) {
                abort(403, 'Unauthorized Access');
            }
        }

        if ($user->role === 'tenant') {
            return view('tenant.request.show', compact('requestRecord'));
        } elseif ($user->role === 'landlord') {
            return view('landlord.request.show', compact('requestRecord'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $requestRecord = MaintenanceRequest::findOrFail($id);
        $user = auth()->user();

        // Authorization check for tenants
        if ($user->role === 'tenant' && $user->tenant->id !== $requestRecord->tenant_id) {
            abort(403);
        }

        // Authorization check for landlords
        if ($user->role === 'landlord') {
            $propertyIds = $user->landlord->properties()->pluck('id');
            if (! in_array($requestRecord->room->property_id, $propertyIds->toArray())) {
                abort(403);
            }
        }

        // Pass 'requestRecord' as 'request' to the view
        if ($user->role === 'tenant') {
            return view('tenant.request.edit', ['request' => $requestRecord]);
        } elseif ($user->role === 'landlord') {
            return view('landlord.request.edit', ['request' => $requestRecord]);
        } else {
            abort(403, 'Unauthorized Access');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $requestRecord = MaintenanceRequest::findOrFail($id);

        if ($user->role === 'tenant' && $user->tenant->id !== $requestRecord->tenant_id) {
            abort(403, 'Unauthorized Access');
        }

        if ($user->role === 'landlord') {
            // Correctly access properties through the landlord relationship on the User model
            $propertyIds = $user->landlord ? $user->landlord->properties()->pluck('id') : collect();
            if ($propertyIds->isEmpty() || ! in_array($requestRecord->room->property_id, $propertyIds->toArray())) {
                abort(403, 'Unauthorized Access');
            }

            // Corrected status validation to match the MaintenanceRequestStatus enum and blade dropdown
            $request->validate(['status' => 'required|in:pending,in_progress,resolved,rejected']);
            $requestRecord->status = $request->status;

            // Optional: Add a field for landlord notes here if you create it in the model
            // $requestRecord->landlord_notes = $request->input('landlord_notes'); // Uncomment if you add this field
        } else { // Tenant attempting to update
            $request->validate([
                'title' => 'required|string|max:50', // Adjusted max length as per store method
                'description' => 'required|string|max:3000', // Adjusted max length as per store method
            ]);
            $requestRecord->title = $request->title;
            $requestRecord->description = $request->description;
        }

        $requestRecord->save();

        return redirect()->route('request.index')->with('toast.success', [
            'title' => 'Request Updated',
            'content' => 'Your request has been updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $requestRecord = MaintenanceRequest::findOrFail($id);

        if ($user->role === 'tenant' && $user->tenant->id !== $requestRecord->tenant_id) {
            abort(403, 'Unauthorized Access');
        }

        if ($user->role === 'landlord') {
            // Correctly access properties through the landlord relationship on the User model
            $propertyIds = $user->landlord ? $user->landlord->properties()->pluck('id') : collect();
            if ($propertyIds->isEmpty() || ! in_array($requestRecord->room->property_id, $propertyIds->toArray())) {
                abort(403, 'Unauthorized Access');
            }
        }

        $requestRecord->delete();

        return redirect()->route('request.index')->with('toast.success', [
            'title' => 'Request Deleted',
            'content' => 'Your request has been deleted successfully.',
        ]);
    }
}
