<x-app-layout title="Dashboard">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Landlord Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            {{-- Main grid container for the two columns --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Left Column: New Cards --}}
                <div class="lg:col-span-1">
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

                {{-- Right Column: Quick Access Buttons, Announcements, Maintenance Requests --}}
                <div class="lg:col-span-2">
                    <x-card.dashboard
                        caption="Quick Access Buttons"
                        value=""
                        wide="true"
                    >
                        {{-- Adjusted to flex-row and justify-center to align buttons in a centered row --}}
                        <div class="flex flex-row justify-center items-center gap-3 flex-wrap">
                            {{-- Buttons with larger text and uniform width --}}
                            <a href="{{ route('property.create') }}"
                               class="py-4 text-xl inline-flex items-center justify-center
                                      bg-lime-600 border border-transparent text-white rounded-md
                                      font-semibold text-center uppercase tracking-widest
                                      transition ease-in-out duration-150
                                      hover:bg-lime-700 focus:bg-lime-700 focus:ring-lime-700 focus:outline-none focus:ring-2 focus:ring-offset-2
                                      active:bg-lime-800 cursor-pointer px-8">
                                Add Properties
                            </a>
                            <a href="{{ route('announcement.create') }}"
                               class="py-4 text-xl inline-flex items-center justify-center
                                      bg-lime-600 border border-transparent text-white rounded-md
                                      font-semibold text-center uppercase tracking-widest
                                      transition ease-in-out duration-150
                                      hover:bg-lime-700 focus:bg-lime-700 focus:ring-lime-700 focus:outline-none focus:ring-2 focus:ring-offset-2
                                      active:bg-lime-800 cursor-pointer px-8">
                                Create Announcement
                            </a>
                            <a href="{{ route('property.index') }}"
                               class="py-4 text-xl inline-flex items-center justify-center
                                      bg-lime-600 border border-transparent text-white rounded-md
                                      font-semibold text-center uppercase tracking-widest
                                      transition ease-in-out duration-150
                                      hover:bg-lime-700 focus:bg-lime-700 focus:ring-lime-700 focus:outline-none focus:ring-2 focus:ring-offset-2
                                      active:bg-lime-800 cursor-pointer px-8">
                                Manage Properties
                            </a>
                        </div>
                    </x-card.dashboard>

                    {{-- Announcements Card (same as tenant, assuming relevant to landlord) --}}
                    <x-card.dashboard
                        caption="Latest Announcements"
                        value=""
                        wide="true"
                    >
                        @if ($landlordAnnouncements->isEmpty())
                            <p class="text-gray-500 text-center">No announcements to display.</p>
                        @else
                            <div class="overflow-x-auto w-full">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                No.
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Type
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Property/Room
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Title
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Date Issued
                                            </th>
                                            <th scope="col" class="relative px-6 py-3">
                                                <span class="sr-only">Details</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($landlordAnnouncements as $index => $announcement)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $index + 1 }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ ucfirst($announcement->type) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @if ($announcement->room)
                                                        {{ $announcement->room->code }} ({{ $announcement->property->name ?? 'N/A' }})
                                                    @elseif ($announcement->property)
                                                        {{ $announcement->property->name }}
                                                    @else
                                                        System Wide
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $announcement->title }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $announcement->created_at->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    {{-- Converted to <a> tag with text button styling --}}
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

                    {{-- Maintenance Requests Card (same as tenant, relevant to landlord) --}}
                    <x-card.dashboard
                        caption="Pending Maintenance Requests"
                        value=""
                        wide="true"
                    >
                        @if ($landlordMaintenanceRequests->isEmpty())
                            <p class="text-gray-500 text-center">No pending maintenance requests.</p>
                        @else
                            <div class="overflow-x-auto w-full">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                No.
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Property/Room
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tenant
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Title
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th scope="col" class="relative px-6 py-3">
                                                <span class="sr-only">Details</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($landlordMaintenanceRequests as $index => $request)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $index + 1 }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $request->room->code ?? 'N/A' }} ({{ $request->room->property->name ?? 'N/A' }})
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $request->tenant->user->name ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $request->title }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $request->status }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    {{-- Converted to <a> tag with text button styling --}}
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
