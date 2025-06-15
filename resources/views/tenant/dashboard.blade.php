<x-app-layout title="Dashboard">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                {{-- Left Column: Property, Room, Rent, Balance, Months Stayed --}}
                <div class="lg:col-span-1">
                    {{-- Today's Statistics Title --}}
                    <div class=""> {{-- Removed mb-6, px-6, py-4 for reduced spacing --}}
                        <h3 class="text-2xl font-bold text-gray-600 mb-2">Todays Statistics</h3> {{-- Changed text-gray-900 to text-gray-600 --}}
                        <p class="text-gray-600 text-sm">
                            {{ \Carbon\Carbon::now()->format('D, d M, Y, h.i A') }}
                        </p>
                    </div>

                    <x-card.dashboard
                        caption="Property Name"
                        value="{{ $tenant->room->property->name ?? 'N/A' }}"
                    />

                    <x-card.dashboard
                        caption="Room Number"
                        value="{{ $tenant->room->code ?? 'N/A' }}"
                    />

                    <x-card.dashboard
                        caption="Rent Due"
                        value="{{ number_format(optional($latestBill)->amount_due ?? 0, 2) . ' PHP' }}"
                        footer="true"
                        footerCaption="Due Date"
                        footerDate="{{ optional(optional($latestBill)->due_date)->format('M d, Y') ?? 'N/A' }}"
                    />

                    <x-card.dashboard
                        caption="Outstanding Balance"
                        value="{{ number_format($outstandingBalance ?? 0, 2) . ' PHP' }}"
                        footer="true"
                        footerCaption="Last Due Date"
                        footerDate="{{ optional(optional($latestOverdueBill)->due_date)->format('M d, Y') ?? 'N/A' }}"
                    />

                    <x-card.dashboard
                        caption="Months Stayed"
                        value="{{ $monthsStayed ?? 0 }}"
                    />
                </div>

                {{-- Right Column: Quick Access Buttons, Announcements, Maintenance Requests --}}
                <div class="lg:col-span-3">
                    <x-card.dashboard
                        caption="Quick Access Buttons"
                        value=""
                        wide="true"
                    >
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 justify-center items-center">
                            <div class="flex justify-center">
                                <a href="{{ route('transaction.index') }}"
                                   class="w-48 h-16 py-4 text-xs inline-flex items-center justify-center
                                          bg-lime-600 border border-transparent text-white rounded-md
                                          font-bold text-center uppercase tracking-widest
                                          transition ease-in-out duration-150
                                          hover:bg-lime-700 focus:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lime-500
                                          active:bg-lime-800 cursor-pointer px-8 shadow-lg">
                                    Pay Bills
                                </a>
                            </div>
                            <div class="flex justify-center">
                                <a href="{{ route('request.create') }}"
                                   class="w-48 h-16 py-4 text-xs inline-flex items-center justify-center
                                          bg-lime-600 border border-transparent text-white rounded-md
                                          font-bold text-center uppercase tracking-widest
                                          transition ease-in-out duration-150
                                          hover:bg-lime-700 focus:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lime-500
                                          active:bg-lime-800 cursor-pointer px-8 shadow-lg text-center">
                                    Create Maintenance <br> Request
                                </a>
                            </div>
                            <div class="flex justify-center">
                                <a href="{{ route('messages.index') }}"
                                   class="w-48 h-16 py-4 text-xs inline-flex items-center justify-center
                                          bg-lime-600 border border-transparent text-white rounded-md
                                          font-bold text-center uppercase tracking-widest
                                          transition ease-in-out duration-150
                                          hover:bg-lime-700 focus:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lime-500
                                          active:bg-lime-800 cursor-pointer px-8 shadow-lg">
                                    Message Landlord
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
                        @if ($announcements->isEmpty())
                            <p class="text-gray-500 text-center">No announcements to display.</p>
                        @else
                            <div class="overflow-x-auto w-full px-6 py-4 max-h-56 overflow-y-auto">
                                {{-- Added table-fixed to ensure column widths are honored --}}
                                <table class="min-w-full divide-y divide-gray-200 table-fixed">
                                    <thead>
                                        <tr>
                                            {{-- Adjusted widths based on screenshot and typical content --}}
                                            <x-table.header class="w-[8%] whitespace-nowrap">No.</x-table.header>
                                            <x-table.header class="w-[17%] whitespace-nowrap">Room Code</x-table.header>
                                            <x-table.header class="w-[40%] whitespace-nowrap">Title</x-table.header>
                                            <x-table.header class="w-[20%] whitespace-nowrap">Date Issued</x-table.header>
                                            <x-table.header class="w-[15%] text-right whitespace-nowrap" :sortable="false">Details</x-table.header>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($announcements as $index => $announcement)
                                            <x-table.row>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <span class="inline-block px-2 py-1 bg-gray-100 text-gray-800 rounded-md text-xs font-semibold">
                                                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <span class="inline-block px-2 py-1 bg-gray-200 text-gray-800 rounded-md text-xs font-semibold">
                                                        {{ $announcement->room->code ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900 font-bold text-base overflow-hidden text-ellipsis whitespace-nowrap"> {{-- Added overflow-hidden for long titles --}}
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
                        @if ($maintenanceRequests->isEmpty())
                            <p class="text-gray-500 text-center">No maintenance requests to display.</p>
                        @else
                            <div class="overflow-x-auto w-full px-6 py-4 max-h-56 overflow-y-auto">
                                {{-- Added table-fixed to ensure column widths are honored --}}
                                <table class="min-w-full divide-y divide-gray-200 table-fixed">
                                    <thead>
                                        <tr>
                                            {{-- Adjusted widths based on screenshot and typical content --}}
                                            <x-table.header class="w-[8%] whitespace-nowrap">No.</x-table.header>
                                            <x-table.header class="w-[17%] whitespace-nowrap">Room #</x-table.header>
                                            <x-table.header class="w-[25%] whitespace-nowrap">Property</x-table.header>
                                            <x-table.header class="w-[35%] whitespace-nowrap">Status</x-table.header>
                                            <x-table.header class="w-[15%] text-right whitespace-nowrap" :sortable="false">Details</x-table.header>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($maintenanceRequests as $index => $request)
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
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $request->room->property->name ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
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
