@props(['property'])

@php
    $icon = match($property->type) {
        'Apartment' => 'ph-building',
        'House' => 'ph-house-line',
        'Dorm' => 'ph-door',
        'Condominium' => 'ph-building-apartment',
        default => 'ph-question', // fallback icon
    };

    $color = match($property->type) {
        'Apartment' => 'text-yellow-600',
        'House' => 'text-pink-600',
        'Dorm' => 'text-orange-600',
        'Condominium' => 'text-purple-600',
        default => 'text-gray-600', // fallback icon
    };
@endphp

<div class="relative w-full max-w-sm">
    <div class="absolute top-2 right-2">
        <x-dropdown position="top" align="right" width="48" class="absolute top-3 right-3 z-10">
            <x-slot name="trigger">
                <i class="ph ph-dots-three-circle-vertical text-xl text-gray-700 cursor-pointer"></i>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link href="#">
                    <i class="ph-bold ph-pencil"></i> Edit
                </x-dropdown-link>
                <x-dropdown-link href="#">
                    <i class="ph-bold ph-trash"></i> Delete
                </x-dropdown-link>
            </x-slot>
        </x-dropdown>
    </div>

    {{-- Clickable Card --}}
    <a href="{{ route('property.rooms', $property->id) }}"
       class="block p-4 h-[400px] bg-white rounded-lg shadow hover:bg-gray-100 transition duration-200">

        <i class="{{ $icon }} ph-bold {{ $color }} text-base text-xl"></i>

        <h2 class="text-lg font-extrabold text-black leading-snug mb-1">
            {{ $property->title }}
        </h2>

        <p class="text-sm text-gray-700 leading-tight mb-2">
            {{ $property->description }}
        </p>

        <hr class="border-black mb-2" />

        <p class="text-sm text-gray-700 leading-tight">
            {{ $property->address }}
        </p>
    </a>
</div>
