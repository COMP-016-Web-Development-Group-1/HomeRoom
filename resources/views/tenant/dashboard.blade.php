<x-app-layout title="Dashboard">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Left Column: Property, Room, Rent, Balance, Months Stayed --}}
                <div class="lg:col-span-1">
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
                <div class="lg:col-span-2">
                    <x-card.dashboard
                        caption="Quick Access Buttons"
                        value=""
                        wide="true"
                    >
                        <div class="flex flex-row justify-center items-center gap-3 flex-wrap">
                            <a href="{{ route('transaction.index') }}"
                                {{-- Unsure if route is correct, please change accordingly. --}}
                               class="py-4 text-xl inline-flex items-center justify-center
                                      bg-lime-600 border border-transparent text-white rounded-md
                                      font-semibold text-center uppercase tracking-widest
                                      transition ease-in-out duration-150
                                      hover:bg-lime-700 focus:bg-lime-700 focus:ring-lime-700 focus:outline-none focus:ring-2 focus:ring-offset-2
                                      active:bg-lime-800 cursor-pointer px-8">
                                Pay Bills
                            </a>
                            <a href="{{ route('request.create') }}"
                               class="py-4 text-xl inline-flex items-center justify-center
                                      bg-lime-600 border border-transparent text-white rounded-md
                                      font-semibold text-center uppercase tracking-widest
                                      transition ease-in-out duration-150
                                      hover:bg-lime-700 focus:bg-lime-700 focus:ring-lime-700 focus:outline-none focus:ring-2 focus:ring-offset-2
                                      active:bg-lime-800 cursor-pointer px-8">
                                Create Maintenance Request
                            </a>

                            <a href="#"
                                {{-- Unsure of the route to use here, please change accordingly. --}}
                               class="py-4 text-xl inline-flex items-center justify-center
                                      bg-red-600 border border-transparent text-white rounded-md
                                      font-semibold text-center uppercase tracking-widest
                                      transition ease-in-out duration-150
                                      hover:bg-red-500 focus:ring-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2
                                      active:bg-red-700 cursor-pointer px-8">
                                Leave Property
                            </a>
                        </div>
                    </x-card.dashboard>

                    <x-card.dashboard
                        caption="Announcements"
                        value=""
                        wide="true"
                    >
                        @if ($announcements->isEmpty())
                            <p class="text-gray-500 text-center">No announcements to display.</p>
                        @else
                            <div class="overflow-x-auto w-full">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            {{-- Added ml-2 for alignment --}}
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider ml-2">
                                                No.
                                            </th>
                                            {{-- Added ml-2 for alignment --}}
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider ml-2">
                                                Room Code
                                            </th>
                                            {{-- Added ml-2 for alignment --}}
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider ml-2">
                                                Title
                                            </th>
                                            {{-- Added ml-2 for alignment --}}
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider ml-2">
                                                Date Issued
                                            </th>
                                            <th scope="col" class="relative px-6 py-3">
                                                <span class="sr-only">Details</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($announcements as $index => $announcement)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $index + 1 }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $announcement->room->code ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $announcement->title }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $announcement->created_at->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('announcement.show', $announcement->id) }}"
                                                       class="text-base font-medium text-lime-600 bg-transparent focus:outline-hidden
                                                              hover:underline hover:text-lime-700 focus:underline focus:text-lime-700">
                                                        Show Details
                                                    </a>
                                                </td>
                                            </tr>
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
                        @if ($maintenanceRequests->isEmpty())
                            <p class="text-gray-500 text-center">No maintenance requests to display.</p>
                        @else
                            <div class="overflow-x-auto w-full">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            {{-- Added ml-2 for alignment --}}
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider ml-2">
                                                No.
                                            </th>
                                            {{-- Added ml-2 for alignment --}}
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider ml-2">
                                                Room Code
                                            </th>
                                            {{-- Added ml-2 for alignment --}}
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider ml-2">
                                                Title
                                            </th>
                                            {{-- Added ml-2 for alignment --}}
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider ml-2">
                                                Status
                                            </th>
                                            <th scope="col" class="relative px-6 py-3">
                                                <span class="sr-only">Details</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($maintenanceRequests as $index => $request)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $index + 1 }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $request->room->code ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $request->title }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $request->status }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('request.show', $request->id) }}"
                                                       class="text-base font-medium text-lime-600 bg-transparent focus:outline-hidden
                                                              hover:underline hover:text-lime-700 focus:underline focus:text-lime-700">
                                                        Show Details
                                                    </a>
                                                </td>
                                            </tr>
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
