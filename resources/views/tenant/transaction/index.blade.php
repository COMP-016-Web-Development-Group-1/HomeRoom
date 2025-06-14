<x-app-layout title="Transactions">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Transactions
        </h2>
    </x-slot>

    <div class="max-w-(--breakpoint-2xl) mx-auto sm:px-6 lg:px-8 relative">
        <div class="bg-white shadow-xs sm:rounded-lg main-blur-area transition-all duration-200 flex flex-col">
            <div class="p-6 text-gray-900 flex flex-col flex-1">
                <h1 class="text-3xl font-bold mb-8 text-lime-800">
                    Manage Transactions
                </h1>

                <div class="mb-4 flex space-x-8">
                    <button
                        id="paybills-tab-btn"
                        type="button"
                        class="tab-btn text-lime-600 font-semibold border-b-2 border-lime-600 focus:outline-none transition-all duration-500"
                        onclick="showTab('paybills')">
                        <span id="paybills-tab-text" class="tab-btn-text transition-all duration-500">Pay Bills</span>
                    </button>
                    <button
                        id="history-tab-btn"
                        type="button"
                        class="tab-btn text-lime-700 font-semibold border-b-2 border-transparent hover:text-lime-600 focus:outline-none transition-all duration-500"
                        onclick="showTab('history')">
                        <span id="history-tab-text" class="tab-btn-text transition-all duration-500">History</span>
                    </button>
                </div>

                <div class="relative flex-1 overflow-hidden">
                    <div id="paybills-content" class="tab-pane">
                        <div class="flex flex-col gap-3 items-center mb-12 mt-1">
                            <x-bill-card
                                title="Monthly Payment"
                                payment-methods="{{ $paymentMethods }}"
                                amount="{{ $monthlyBill ? '₱' . number_format($monthlyBill->amount_due, 2) : '₱0.00' }}"
                                date="{{ $monthlyBill && $monthlyBill->due_date ? $monthlyBill->due_date->format('m-d-Y') : '-' }}"
                                button-text="Pay Bill"
                                button-url="{{ $monthlyBill ? route('pay-bills', ['type' => 'monthly']) : '#' }}"
                                :disabled="!$monthlyBill"
                                class="max-w-5xl py-12 px-16"
                            />
                            <x-bill-card
                                title="Outstanding Bill"
                                payment-methods="{{ $paymentMethods }}"
                                amount="₱{{ number_format($totalOutstandingBill, 2) }}"
                                date="{{ $nextOutstandingDueDate ? \Carbon\Carbon::parse($nextOutstandingDueDate)->format('m-d-Y') : '-' }}"
                                button-text="Pay Bill"
                                button-url="{{ $totalOutstandingBill > 0 ? route('pay-bills', ['type' => 'outstanding']) : '#' }}"
                                :disabled="$totalOutstandingBill == 0"
                                class="max-w-5xl py-12 px-16"
                            />
                        </div>
                    </div>
                    <div id="history-content" class="tab-pane hidden">
                        <x-table.container id="history-table">
                            <x-slot name="header">
                                <th class="bg-lime-700 text-white">Property</th>
                                <th class="bg-lime-700 text-white">Room #</th>
                                <th class="bg-lime-700 text-white">Bill Due Date</th>
                                <th class="bg-lime-700 text-white">Amount</th>
                                <th class="bg-lime-700 text-white">Payment Method</th>
                                <th class="bg-lime-700 text-white">Status</th>
                                <th class="bg-lime-700 text-white">Reference #</th>
                                <th class="bg-lime-700 text-white">Proof</th>
                            </x-slot>
                            <x-slot name="body">
                                @forelse($historyTransactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->tenant->room->property->name ?? '-' }}</td>
                                        <td>{{ $transaction->tenant->room->name ?? '-' }}</td>
                                        <td>
                                            {{ $transaction->bill?->due_date ? \Carbon\Carbon::parse($transaction->bill->due_date)->format('m/d/Y') : '-' }}
                                        </td>
                                        <td>
                                            {{ $transaction->amount ? '₱' . number_format($transaction->amount, 2) : '-' }}
                                        </td>
                                        <td>
                                            {{ ucfirst($transaction->payment_method) }}
                                        </td>
                                        <td>
                                            <x-status :value="$transaction->status" />
                                        </td>
                                        <td>
                                            {{ $transaction->reference_number ?? '-' }}
                                        </td>
                                        <td>
                                            @if($transaction->proof_photo)
                                                <x-button onclick="showPhotoModal('{{ asset('storage/' . ltrim($transaction->proof_photo, '/')) }}')">
                                                    See Photo
                                                </x-button>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-gray-400 py-6">No transactions found.</td>
                                    </tr>
                                @endforelse
                            </x-slot>
                        </x-table.container>
                    </div>
                </div>

                <x-modal name="photo-modal" :show="false" maxWidth="md">
                    <div id="photo-modal-content" class="flex flex-col items-center justify-center p-4">
                        <img id="modal-photo-img" src="" alt="Transaction Photo" class="max-w-full max-h-[60vh] rounded shadow">
                    </div>
                </x-modal>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let currentTab = 'paybills';
            const paybillsBtn = document.getElementById('paybills-tab-btn');
            const historyBtn = document.getElementById('history-tab-btn');
            const paybillsContent = document.getElementById('paybills-content');
            const historyContent = document.getElementById('history-content');
            const paybillsText = document.getElementById('paybills-tab-text');
            const historyText = document.getElementById('history-tab-text');

            window.showTab = function(tab) {
                if (tab === currentTab) return;

                // Tab label styling
                if (tab === 'paybills') {
                    paybillsBtn.classList.add('text-lime-600', 'border-lime-600');
                    paybillsBtn.classList.remove('text-lime-700', 'border-transparent');
                    historyBtn.classList.add('text-lime-700', 'border-transparent');
                    historyBtn.classList.remove('text-lime-600', 'border-lime-600');

                    paybillsText.classList.add('tab-btn-text-active');
                    paybillsText.classList.remove('tab-btn-text-inactive');
                    historyText.classList.remove('tab-btn-text-active');
                    historyText.classList.add('tab-btn-text-inactive');
                } else {
                    historyBtn.classList.add('text-lime-600', 'border-lime-600');
                    historyBtn.classList.remove('text-lime-700', 'border-transparent');
                    paybillsBtn.classList.add('text-lime-700', 'border-transparent');
                    paybillsBtn.classList.remove('text-lime-600', 'border-lime-600');

                    historyText.classList.add('tab-btn-text-active');
                    historyText.classList.remove('tab-btn-text-inactive');
                    paybillsText.classList.remove('tab-btn-text-active');
                    paybillsText.classList.add('tab-btn-text-inactive');
                }

                // Show/Hide tab panes using Tailwind's hidden class
                if(tab === 'paybills') {
                    paybillsContent.classList.remove('hidden');
                    historyContent.classList.add('hidden');
                } else {
                    paybillsContent.classList.add('hidden');
                    historyContent.classList.remove('hidden');
                }

                currentTab = tab;
            };

            // Set initial state
            paybillsContent.classList.remove('hidden');
            historyContent.classList.add('hidden');
            paybillsText.classList.add('tab-btn-text-active');
            historyText.classList.add('tab-btn-text-inactive');
        });

        function showPhotoModal(photoUrl) {
            const img = document.getElementById('modal-photo-img');
            img.src = photoUrl || '';
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'photo-modal' }));
        }
    </script>
    <style>
        .tab-pane {
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .tab-btn-text {
            display: inline-block;
            transition:
                opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                transform 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                color 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .tab-btn-text-active {
            opacity: 1;
            transform: scale(1.1);
            color: #65a30d;
            filter: drop-shadow(0 2px 2px rgba(101,163,13,0.10));
        }
        .tab-btn-text-inactive {
            opacity: 0.6;
            transform: scale(1);
            color: #365314;
            filter: none;
        }
        .shadow {
            box-shadow: 0 4px 10px rgba(0,0,0,0.07), 0 1.5px 4px rgba(0,0,0,0.03);
        }
        .paybill-card {
            font-size: 1.07rem;
            min-height: 180px;
            max-width: 900px;
            padding: 3rem 4rem !important;
        }
        .bg-lime-600 {
            background-color: #65a30d !important;
        }
        .bg-lime-600:hover, .hover\:bg-lime-700:hover {
            background-color: #365314 !important;
        }
        .text-lime-600 {
            color: #65a30d !important;
        }
        .text-lime-700 {
            color: #365314 !important;
        }
        .border-lime-600 {
            border-color: #65a30d !important;
        }
        /* Add styles for disabled button */
        .opacity-50 {
            opacity: 0.5;
        }
        .cursor-not-allowed {
            cursor: not-allowed;
        }
    </style>
</x-app-layout>
