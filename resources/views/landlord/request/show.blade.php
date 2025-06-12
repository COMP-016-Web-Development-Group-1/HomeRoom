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
                {{-- Add the Edit Status button here for landlords --}}
                @if (auth()->check() && auth()->user()->role === 'landlord')
                    <div>
                        <x-a variant="primary" :href="route('request.edit', $requestRecord)">
                            <i class="ph-bold ph-pencil"></i> Edit Status
                        </x-a>
                    </div>
                @endif
            </div>

            {{-- x-card.request now dynamically displays status and tenant name --}}
            <x-card.request :request="$requestRecord" :full="true" />
        </div>
    </div>
</x-app-layout>
