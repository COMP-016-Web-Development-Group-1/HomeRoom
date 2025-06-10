@props(['announcement'])

@php
    $announcement_type_icon = [
        'all' => ['text' => 'All', 'icon' => 'ph-globe', 'color' => 'blue'],
        'property_wide' => ['text' => 'Property Wide', 'icon' => 'ph-house', 'color' => 'yellow'],
        'room_specific' => ['text' => 'Room Specific', 'icon' => 'ph-door', 'color' => 'pink'],
    ];

    // Determine announcement type based on foreign keys
    if ($announcement->room_id) {
        $type = 'room_specific';
    } elseif ($announcement->property_id) {
        $type = 'property_wide';
    } else {
        $type = 'all';
    }
@endphp

<div class="bg-white shadow p-4 sm:rounded-lg border-l-4 border-lime-800 mb-8">
    <div class="flex items-center justify-between">
        <x-badge :color="$announcement_type_icon[$type]['color']" :icon="$announcement_type_icon[$type]['icon']"
            :interactive="true">{{ $announcement_type_icon[$type]['text'] }}</x-badge>

        <x-dropdown position="bottom" align="default" width="48" :fullWidth="false">
            <x-slot name="trigger">
                <x-button variant="clean">
                    <i class="ph-bold ph-dots-three-vertical"></i>
                </x-button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link href="#">
                    <i class="ph-bold ph-pencil"></i> Edit
                </x-dropdown-link>

                <x-dropdown-link href="#" color="red">
                    <i class="ph-bold ph-trash"></i> Delete
                </x-dropdown-link>
            </x-slot>
        </x-dropdown>
    </div>
    <h3 class="font-bold text-2xl my-2">{{ $announcement->title }}</h3>

    <p class="text-gray-800 pr-4 max-w-4xl block sm:hidden">
        {{ Str::words($announcement->description, 25) }}
    </p>
    <p class="text-gray-800 pr-4 max-w-4xl hidden sm:block">
        {{ Str::words($announcement->description, 50) }}
    </p>
    <div class="flex items-center justify-between mt-4 text-sm">
        <div class="flex items-center gap-x-4">
            <div class="text-gray-600 flex items-center gap-x-1">
                <i class="ph-fill ph-calendar-dots text-lime-600 text-base"></i>
                {{ $announcement->created_at->toFormattedDateString() }}
            </div>
            <div class="text-gray-600 flex items-center gap-x-1">
                <i class="ph-fill ph-map-pin text-lime-600 text-base"></i>
                @if ($announcement->room_id && $announcement->room)
                    {{ $announcement->room->name ?? 'Room' }}
                @elseif($announcement->property_id && $announcement->property)
                    {{ $announcement->property->name ?? 'Property' }}
                @else
                    All Properties
                @endif
            </div>
        </div>

        <div>
            <x-button variant="dark">View Details</x-button>
        </div>
    </div>

</div>
