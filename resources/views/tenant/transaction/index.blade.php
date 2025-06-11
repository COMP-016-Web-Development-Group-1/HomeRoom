<x-app-layout title="Transactions">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Transactions
        </h2>
    </x-slot>

    <div class="max-w-(--breakpoint-2xl) mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-xs sm:rounded-lg">
            <div class="p-6 text-gray-900">

                <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Transaction Dashboard</h1>

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

                <div class="relative overflow-hidden" style="min-height:690px">
                    {{-- Pay Bills content --}}
                    <div id="paybills-content" class="tab-pane transition-transform duration-500 ease-in-out"
                        style="display: block; transform: translateX(0%); position: absolute; width: 100%;">
                        <div class="flex flex-col gap-3 items-center mb-12 mt-1">
                            {{-- Monthly Payment Card --}}
                            <x-bill-card
                                title="Monthly Payment"
                                payment-methods="GCash, Maya, Credit Card"
                                amount="₱5000.00"
                                date="01-02-2025"
                                button-text="Pay Bill"
                                button-url="#"
                                class="max-w-5xl py-12 px-16"
                            />
                            {{-- Outstanding Bill Card --}}
                            <x-bill-card
                                title="Outstanding Bill"
                                payment-methods="GCash, Maya, Credit Card"
                                amount="₱0.00"
                                date="01-02-2025"
                                button-text="Pay Bill"
                                button-url="#"
                                class="max-w-5xl py-12 px-16"
                            />
                        </div>
                    </div>

                    {{-- History content --}}
                    <div id="history-content" class="tab-pane transition-transform duration-500 ease-in-out"
                        style="display: none; transform: translateX(100%); position: absolute; width: 100%;">
                        <x-table.container id="history-table">
                            <x-slot name="header">
                                <th class="bg-lime-700 text-white">Properties</th>
                                <th class="bg-lime-700 text-white">Room #</th>
                                <th class="bg-lime-700 text-white">Type</th>
                                <th class="bg-lime-700 text-white">Date</th>
                                <th class="bg-lime-700 text-white">Amount</th>
                                <th class="bg-lime-700 text-white">Status</th>
                                <th class="bg-lime-700 text-white">Photo</th>
                            </x-slot>
                            <x-slot name="body">
                                @foreach($historyTransactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->tenant->room->property->name ?? '-' }}</td>
                                        <td>{{ $transaction->tenant->room->name ?? '-' }}</td>
                                        <td>{{ $transaction->type ?? '-' }}</td>
                                        <td>{{ $transaction->due_date ? \Carbon\Carbon::parse($transaction->due_date)->format('m/d/Y') : '-' }}</td>
                                        <td>{{ $transaction->amount ? '₱' . number_format($transaction->amount, 2) : '-' }}</td>
                                        <td>
                                            <x-status :value="$transaction->status" />
                                        </td>
                                        <td>
                                            <x-button onclick="showPhotoModal('{{ $transaction->photo }}')">
                                                See Photo
                                            </x-button>
                                        </td>
                                    </tr>
                                @endforeach
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
        let currentTab = 'paybills';

        function showTab(tab) {
            if (tab === currentTab) return;

            const paybillsBtn = document.getElementById('paybills-tab-btn');
            const historyBtn = document.getElementById('history-tab-btn');
            const paybillsContent = document.getElementById('paybills-content');
            const historyContent = document.getElementById('history-content');
            const paybillsText = document.getElementById('paybills-tab-text');
            const historyText = document.getElementById('history-tab-text');

            if (tab === 'paybills') {
                historyText.classList.remove('tab-btn-text-active');
                historyText.classList.add('tab-btn-text-inactive');
                paybillsText.classList.remove('tab-btn-text-inactive');
                paybillsText.classList.add('tab-btn-text-active');
            } else {
                paybillsText.classList.remove('tab-btn-text-active');
                paybillsText.classList.add('tab-btn-text-inactive');
                historyText.classList.remove('tab-btn-text-inactive');
                historyText.classList.add('tab-btn-text-active');
            }

            if (tab === 'paybills') {
                paybillsBtn.classList.add('text-lime-600', 'border-lime-600');
                paybillsBtn.classList.remove('text-lime-700', 'border-transparent');
                historyBtn.classList.add('text-lime-700', 'border-transparent');
                historyBtn.classList.remove('text-lime-600', 'border-lime-600');
            } else {
                historyBtn.classList.add('text-lime-600', 'border-lime-600');
                historyBtn.classList.remove('text-lime-700', 'border-transparent');
                paybillsBtn.classList.add('text-lime-700', 'border-transparent');
                paybillsBtn.classList.remove('text-lime-600', 'border-lime-600');
            }

            let outgoing, incoming, direction;
            if (tab === 'paybills') {
                outgoing = historyContent;
                incoming = paybillsContent;
                direction = -1;
            } else {
                outgoing = paybillsContent;
                incoming = historyContent;
                direction = 1;
            }

            incoming.style.display = 'block';
            incoming.style.transform = `translateX(${direction * 100}%)`;

            setTimeout(() => {
                outgoing.style.transform = `translateX(${-direction * 100}%)`;
                incoming.style.transform = 'translateX(0%)';
            }, 20);

            setTimeout(() => {
                outgoing.style.display = 'none';
                outgoing.style.transform = `translateX(${direction * 100}%)`;
            }, 520);

            currentTab = tab;
        }

        function showPhotoModal(photoUrl) {
            const img = document.getElementById('modal-photo-img');
            img.src = photoUrl || '';
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'photo-modal' }));
        }

        window.addEventListener('DOMContentLoaded', () => {
            document.getElementById('paybills-tab-text').classList.add('tab-btn-text-active');
            document.getElementById('history-tab-text').classList.add('tab-btn-text-inactive');
        });
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
    </style>
</x-app-layout>
