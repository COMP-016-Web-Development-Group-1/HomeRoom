@php
    $request_type_icon = [
        'tenant' => ['text' => 'Tenant', 'icon' => 'ph-user-circle', 'color' => 'lime'],
        'room' => ['text' => 'Room', 'icon' => 'ph-door', 'color' => 'yellow'],
        'status' => ['text' => 'Status', 'icon' => 'ph-heartbeat', 'color' => 'red'],
    ];

    // Determine request type based on status
    if ($request->status === 'pending') {
        $type = 'pending';
    } elseif ($request->status === 'in_progress') {
        $type = 'in_progress';
    } elseif ($request->status === 'resolved') {
        $type = 'resolved';
    } elseif ($request->status === 'rejected') {
        $type = 'rejected';
    } else {
        $type = 'system';
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
                    <x-a variant="text" :href="route('tenant.request.index')">
                        Back To Requests
                    </x-a>
                </div>
            </div>

            <x-card.request :request="$request" :full="true" />
        </div>
</x-app-layout>
