<x-app-layout title="Announcements">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Announcements
        </h2>
    </x-slot>

    <div class="max-w-(--breakpoint-2xl) mx-auto sm:px-6 lg:px-8">
        <div class="text-gray-900">
            <div class="flex items-end justify-between gap-x-3 mb-4">
                <div class="flex items-center flex-wrap gap-2">
                    <x-badge color="blue" icon="ph-globe" :interactive="true" type="a"
                        href="{{ route('announcement.index', ['type' => 'all']) }}">All</x-badge>
                    <x-badge color="orange" icon="ph-users-three" :interactive="true" type="a"
                        href="{{ route('announcement.index', ['type' => 'system']) }}">System</x-badge>
                    <x-badge color="yellow" icon="ph-house" :interactive="true" type="a"
                        href="{{ route('announcement.index', ['type' => 'property']) }}">Property</x-badge>
                    <x-badge color="pink" icon="ph-door" :interactive="true" type="a"
                        href="{{ route('announcement.index', ['type' => 'room']) }}">Room</x-badge>
                </div>
                <div>
                    <x-a variant="dark" :href="route('announcement.create')">
                        <i class="ph-bold ph-plus"></i>
                        New Announcement
                    </x-a>
                </div>
            </div>

            @forelse ($announcements as $announcement)
                <x-card.announcement :announcement="$announcement" />
            @empty
                <p>No Announcements</p>
            @endforelse

        </div>
    </div>
</x-app-layout>
