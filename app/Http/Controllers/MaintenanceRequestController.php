<?php

namespace App\Http\Controllers;

use App\Models\{MaintenanceRequest, Tenant, Room};
use Illuminate\Http\Request;

class MaintenanceRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'landlord') {
            $propertyIds = $user->properties()->pluck('id');
            $roomIds = Room::whereIn('property_id', $propertyIds)->pluck('id');
            $requests = MaintenanceRequest::whereIn('room_id', $roomIds)
                ->with(['room', 'tenant'])
                ->latest()
                ->get();

            return view('landlord.request.index', compact('requests'));
        } elseif ($user->role === 'tenant') {
            $tenant = $user->tenant;
            $requests = $tenant && $tenant->room_id
                ? MaintenanceRequest::where('room_id', $tenant->room_id)->with(['room', 'tenant'])->latest()->get()
                : collect();

            return view('tenant.request.index', compact('requests'));
        }

        abort(403);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();

        if ($user->role === 'tenant') {
            $tenant = $user->tenant;

            if (!$tenant || !$tenant->room) {
                return redirect()->back()->withErrors(['You must have a room assigned to create a maintenance request.']);
            }

            return view('tenant.request.create', ['room' => $tenant->room]);
        }

        abort(403);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $user = auth()->user();
        $tenant = $user->tenant;

        if (!$tenant || !$tenant->room) {
            return redirect()->back()->withErrors(['You must be assigned a room to create a maintenance request.']);
        }

        MaintenanceRequest::create([
            'tenant_id' => $tenant->id,
            'room_id' => $tenant->room_id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Maintenance request submitted successfully.');
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
                abort(403);
            }
        }

        $view = $user->role === 'landlord' ? 'landlord.request.show' : 'tenant.request.show';
        return view($view, compact('requestRecord'));
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

        $view = $user->role === 'landlord' ? 'landlord.request.edit' : 'tenant.request.edit';
        return view($view, compact('requestRecord'));
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

        $route = $user->role === 'landlord' ? 'landlord.request.index' : 'tenant.request.index';
        return redirect()->route($route)->with('success', 'Maintenance request updated.');
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

        $route = $user->role === 'landlord' ? 'landlord.request.index' : 'tenant.request.index';
        return redirect()->route($route)->with('success', 'Maintenance request deleted.');
    }
}
