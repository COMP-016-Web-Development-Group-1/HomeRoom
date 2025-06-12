@php
    $request_type_icon = [
        'tenant' => ['text' => 'Tenant', 'icon' => 'ph-user-circle', 'color' => 'lime'],
        'room' => ['text' => 'Room', 'icon' => 'ph-door', 'color' => 'yellow'],
        'status' => ['text' => 'Status', 'icon' => 'ph-heartbeat', 'color' => 'red'], // This 'status' key is generally a fallback or for generic display.
    ];

    // Determine request type based on status for display purposes in this specific view
    // Note: The $request object here refers to the $requestRecord passed from the controller.
    $type_display = 'status'; // Default display type for the badge if no specific status match
    $status_text = 'Unknown Status'; // Default text

    if (isset($requestRecord->status)) { // Use $requestRecord here as it's the variable passed
        if ($requestRecord->status === 'pending') {
            $type_display = 'status'; // Or a custom type if you want a specific icon/color for pending
            $status_text = 'Pending';
        } elseif ($requestRecord->status === 'in_progress') {
            $type_display = 'status'; // Similar for in_progress
            $status_text = 'In Progress';
        } elseif ($requestRecord->status === 'resolved') {
            $type_display = 'status'; // Similar for resolved
            $status_text = 'Resolved';
        } elseif ($requestRecord->status === 'rejected') {
            $type_display = 'status'; // Similar for rejected
            $status_text = 'Rejected';
        }
    }
@endphp

<x-app-layout title="Maintenance Requests">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Maintenance Requests
        </h2>
    </x-slot>

    <div class="max-w-(--breakpoint-2xl) mx-auto sm:px-6 lg:px-8">
        <div class="text-gray-900">
            <div class="flex items-end justify-between gap-x-3 mb-2">
                <div class="font-bold text-2xl flex items-center">
                    <i class="ph-bold ph-caret-left text-lime-600"></i>
                    <x-a variant="text" :href="route('request.index')"> {{-- Changed to request.index --}}
                        Back To Requests
                    </x-a>
                </div>
            </div>

            {{-- Pass the correct status text and type_display to x-card.request --}}
            <x-card.request :request="$requestRecord" :full="true" :statusText="$status_text" :typeDisplay="$type_display" />
        </div>
    </div>
</x-app-layout>
