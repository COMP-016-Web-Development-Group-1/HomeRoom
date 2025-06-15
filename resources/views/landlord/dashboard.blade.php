<x-app-layout title="Dashboard">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Landlord Dashboard
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="w-full px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

                <div class="lg:col-span-1">

                    <div class="">
                        <h3 class="text-2xl font-bold text-gray-600 mb-2">Overall Statistics</h3>
                        <p class="text-gray-600 text-sm">
                            {{ \Carbon\Carbon::now()->format('D, d M, Y, h.i A') }}
                        </p>
                    </div>

                    <x-card.dashboard
                        caption="Total Properties"
                        value="{{ $totalProperties ?? 0 }}"
                    />

                    <x-card.dashboard
                        caption="Total Occupants"
                        value="{{ $totalOccupants ?? 0 }}"
                    />

                    <x-card.dashboard
                        caption="Total Monthly Rent Due"
                        value="{{ number_format($totalMonthlyRentDue ?? 0, 2) . ' PHP' }}"
                    />

                    <x-card.dashboard
                        caption="Total Outstanding Rent"
                        value="{{ number_format($totalOutstandingRent ?? 0, 2) . ' PHP' }}"
                    />

                    <x-card.dashboard
                        caption="Total Rent Collected"
                        value="{{ number_format($totalRentCollected ?? 0, 2) . ' PHP' }}"
                    />
                </div>

                <div class="lg:col-span-3">
                    <x-card.dashboard
                        caption="Quick Access Buttons"
                        value=""
                        wide="true"
                    >
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 justify-center items-center">
                            <div class="flex justify-center">
                                <a href="{{ route('property.create') }}"
                                   class="w-48 h-16 py-4 text-xs inline-flex items-center justify-center
                                          bg-lime-600 border border-transparent text-white rounded-md
                                          font-bold text-center uppercase tracking-widest
                                          transition ease-in-out duration-150
                                          hover:bg-lime-700 focus:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lime-500
                                          active:bg-lime-800 cursor-pointer px-8 shadow-lg">
                                    Add Properties
                                </a>
                            </div>
                            <div class="flex justify-center">
                                <a href="{{ route('announcement.create') }}"
                                   class="w-48 h-16 py-4 text-xs inline-flex items-center justify-center
                                          bg-lime-600 border border-transparent text-white rounded-md
                                          font-bold text-center uppercase tracking-widest
                                          transition ease-in-out duration-150
                                          hover:bg-lime-700 focus:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lime-500
                                          active:bg-lime-800 cursor-pointer px-8 shadow-lg">
                                    Create Announcement
                                </a>
                            </div>
                            <div class="flex justify-center">
                                <a href="{{ route('property.index') }}"
                                   class="w-48 h-16 py-4 text-xs inline-flex items-center justify-center
                                          bg-lime-600 border border-transparent text-white rounded-md
                                          font-bold text-center uppercase tracking-widest
                                          transition ease-in-out duration-150
                                          hover:bg-lime-700 focus:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lime-500
                                          active:bg-lime-800 cursor-pointer px-8 shadow-lg">
                                    Manage Properties
                                </a>
                            </div>
                        </div>
                    </x-card.dashboard>


                    <x-card.dashboard
                        caption="Announcements"
                        value=""
                        wide="true"
                    >
                        <x-slot name="headerActions">
                            <a href="{{ route('announcement.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-lime-600 text-white rounded-md
                            font-semibold text-xs uppercase shadow-sm
                            hover:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2
                            transition ease-in-out duration-150">
                                See All
                            </a>
                        </x-slot>
                        @if ($landlordAnnouncements->isEmpty())
                            <p class="text-gray-500 text-center">No announcements to display.</p>
                        @else
                            <div class="overflow-x-auto w-full px-6 py-4 max-h-56 overflow-y-auto">
                                <table class="min-w-full divide-y divide-gray-200 table-fixed">
                                    <thead>
                                        <tr>
                                            <x-table.header class="w-[8%] whitespace-nowrap">No.</x-table.header>
                                            <x-table.header class="w-[17%] whitespace-nowrap">Type</x-table.header>
                                            <x-table.header class="w-[17%] whitespace-nowrap">Property/Room</x-table.header>
                                            <x-table.header class="w-[28%] whitespace-nowrap">Title</x-table.header>
                                            <x-table.header class="w-[20%] whitespace-nowrap">Date Issued</x-table.header>
                                            <x-table.header class="w-[10%] text-right whitespace-nowrap" :sortable="false">Details</x-table.header>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($landlordAnnouncements as $index => $announcement)
                                            <x-table.row>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <span class="inline-block px-2 py-1 bg-gray-100 text-gray-800 rounded-md text-xs font-semibold">
                                                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <span class="inline-block px-2 py-1 bg-gray-200 text-gray-800 rounded-md text-xs font-semibold">
                                                        {{ ucfirst($announcement->type) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @if ($announcement->room)
                                                        <span class="inline-block px-2 py-1 bg-gray-200 text-gray-800 rounded-md text-xs font-semibold">
                                                            {{ $announcement->room->code }}
                                                        </span>
                                                        ({{ $announcement->property->name ?? 'N/A' }})
                                                    @elseif ($announcement->property)
                                                        {{ $announcement->property->name }}
                                                    @else
                                                        System Wide
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900 font-bold text-base overflow-hidden text-ellipsis whitespace-nowrap">
                                                    {{ $announcement->title }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $announcement->created_at->format('d M, Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('announcement.show', $announcement->id) }}"
                                                       class="inline-flex items-center justify-center px-4 py-2 bg-lime-600 text-white rounded-md
                                                              font-semibold text-xs uppercase shadow-sm
                                                              hover:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2
                                                              transition ease-in-out duration-150">
                                                        Details
                                                    </a>
                                                </td>
                                            </x-table.row>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </x-card.dashboard>

                    <x-card.dashboard
                        caption="Maintenance Requests"
                        value=""
                        wide="true"
                    >
                        <x-slot name="headerActions">
                            <a href="{{ route('request.index') }}"
                               class="inline-flex items-center justify-center px-4 py-2 bg-lime-600 text-white rounded-md
                               font-semibold text-xs uppercase shadow-sm
                               hover:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2
                               transition ease-in-out duration-150">
                                See All
                            </a>
                        </x-slot>
                        @if ($landlordMaintenanceRequests->isEmpty())
                            <p class="text-gray-500 text-center">No pending maintenance requests.</p>
                        @else
                            <div class="overflow-x-auto w-full px-6 py-4 max-h-56 overflow-y-auto">
                                <table class="min-w-full divide-y divide-gray-200 table-fixed">
                                    <thead>
                                        <tr>
                                            <x-table.header class="w-[8%] whitespace-nowrap">No.</x-table.header>
                                            <x-table.header class="w-[25%] whitespace-nowrap">Property/Room</x-table.header>
                                            <x-table.header class="w-[20%] whitespace-nowrap">Tenant</x-table.header>
                                            <x-table.header class="w-[27%] whitespace-nowrap">Title</x-table.header>
                                            <x-table.header class="w-[10%] whitespace-nowrap">Status</x-table.header>
                                            <x-table.header class="w-[10%] text-right whitespace-nowrap" :sortable="false">Details</x-table.header>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($landlordMaintenanceRequests as $index => $request)
                                            <x-table.row>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <span class="inline-block px-2 py-1 bg-gray-100 text-gray-800 rounded-md text-xs font-semibold">
                                                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <span class="inline-block px-2 py-1 bg-gray-200 text-gray-800 rounded-md text-xs font-semibold">
                                                        {{ $request->room->code ?? 'N/A' }}
                                                    </span>
                                                    ({{ $request->room->property->name ?? 'N/A' }})
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $request->tenant->user->name ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900 font-bold text-base overflow-hidden text-ellipsis whitespace-nowrap">
                                                    {{ $request->title }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    @php
                                                        $statusClass = '';
                                                        switch ($request->status) {
                                                            case 'resolved':
                                                                $statusClass = 'bg-green-500';
                                                                break;
                                                            case 'pending':
                                                                $statusClass = 'bg-yellow-500';
                                                                break;
                                                            case 'in_progress':
                                                                $statusClass = 'bg-lime-500';
                                                                break;
                                                            case 'rejected':
                                                                $statusClass = 'bg-red-500';
                                                                break;
                                                            default:
                                                                $statusClass = 'bg-gray-400';
                                                                break;
                                                        }
                                                    @endphp
                                                    <div class="flex items-center">
                                                        <span class="w-3 h-3 rounded-full {{ $statusClass }} mr-2"></span>
                                                        <span class="capitalize">{{ str_replace('_', ' ', $request->status) }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('request.show', $request->id) }}"
                                                       class="inline-flex items-center justify-center px-4 py-2 bg-lime-600 text-white rounded-md
                                                              font-semibold text-xs uppercase shadow-sm
                                                              hover:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2
                                                              transition ease-in-out duration-150">
                                                        Details
                                                    </a>
                                                </td>
                                            </x-table.row>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </x-card.dashboard>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
