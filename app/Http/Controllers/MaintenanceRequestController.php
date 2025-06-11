<?php

namespace App\Http\Controllers;
use App\Enums\MaintenanceRequestStatus;
use App\Models\{MaintenanceRequest, Tenant, Room};
use Illuminate\Http\Request;

class MaintenanceRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $role = auth()->user()->role;

        return match ($role) {
            'landlord' => view('landlord.request.index'),
            'tenant' => view('tenant.request.index'),
            default => abort(403),
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
        $requestRecord = MaintenanceRequest::with(['room', 'tenant'])->findOrFail($id);

        if ($user->role === 'tenant' && $user->tenant->id !== $requestRecord->tenant_id) {
            abort(403);
        }

        if ($user->role === 'landlord') {
            $propertyIds = $user->properties()->pluck('id');
            if (!in_array($requestRecord->room->property_id, $propertyIds->toArray())) {
                abort(403, 'Went Here');
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
        $user = auth()->user();
        $requestRecord = MaintenanceRequest::findOrFail($id);

        if ($user->role === 'tenant' && $user->tenant->id !== $requestRecord->tenant_id) {
            abort(403);
        }

        if ($user->role === 'landlord') {
            $propertyIds = $user->properties()->pluck('id');
            if (!in_array($requestRecord->room->property_id, $propertyIds->toArray())) {
                abort(403);
            }
        }

        if ($user->role === 'tenant') {
            return view('tenant.request.edit', compact('requestRecord'));
        } elseif ($user->role === 'landlord') {
            return view('landlord.request.edit', compact('requestRecord'));
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
            abort(403);
        }

        if ($user->role === 'landlord') {
            $propertyIds = $user->properties()->pluck('id');
            if (!in_array($requestRecord->room->property_id, $propertyIds->toArray())) {
                abort(403);
            }

            $request->validate(['status' => 'required|in:pending,in_progress,completed']);
            $requestRecord->status = $request->status;
        } else {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
            ]);
            $requestRecord->title = $request->title;
            $requestRecord->description = $request->description;
        }

        $requestRecord->save();


        return redirect()->route('request.index')->with('toast.success', [
            'title' => 'Request Updated',
            'content' => 'Your request has been updated successfully.',
        ]);
        ;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $requestRecord = MaintenanceRequest::findOrFail($id);

        if ($user->role === 'tenant' && $user->tenant->id !== $requestRecord->tenant_id) {
            abort(403);
        }

        if ($user->role === 'landlord') {
            $propertyIds = $user->properties()->pluck('id');
            if (!in_array($requestRecord->room->property_id, $propertyIds->toArray())) {
                abort(403);
            }
        }

        $requestRecord->delete();

        return redirect()->route('request.index')->with('toast.success', [
            'title' => 'Request Deleted',
            'content' => 'Your request has been deleted successfully.',
        ]);
    }
}
