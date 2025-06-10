//TODO: ADD functionality to acknowledge pending payments

<x-app-layout title="Transactions">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Transactions
        </h2>
    </x-slot>

    <div class="max-w-(--breakpoint-2xl) mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-xs sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-4 flex space-x-8">
                    <button
                        id="pending-tab-btn"
                        type="button"
                        class="tab-btn text-lime-600 font-semibold border-b-2 border-lime-600 focus:outline-none transition-all duration-500"
                        onclick="showTab('pending')">
                        <span id="pending-tab-text" class="tab-btn-text transition-all duration-500">Pending Payments</span>
                    </button>
                    <button
                        id="history-tab-btn"
                        type="button"
                        class="tab-btn text-lime-700 font-semibold border-b-2 border-transparent hover:text-lime-600 focus:outline-none transition-all duration-500"
                        onclick="showTab('history')">
                        <span id="history-tab-text" class="tab-btn-text transition-all duration-500">History</span>
                    </button>
                </div>

                <div class="relative overflow-hidden" style="min-height:160px">
                    <div id="pending-content" class="tab-pane transition-transform duration-500 ease-in-out"
                        style="display: block; transform: translateX(0%); position: absolute; width: 100%;">
                        <x-table.container id="pending-payments-table">
                            <x-slot name="header">
                                <th class="bg-lime-700 text-white">Properties</th>
                                <th class="bg-lime-700 text-white">Room #</th>
                                <th class="bg-lime-700 text-white">Type</th>
                                <th class="bg-lime-700 text-white">Date</th>
                                <th class="bg-lime-700 text-white">Amount</th>
                                <th class="bg-lime-700 text-white">Status</th>
                                <th class="bg-lime-700 text-white">Photo</th>
                                <th class="bg-lime-700 text-white">Actions</th>
                            </x-slot>
                            <x-slot name="body">
                                @foreach($pendingTransactions as $transaction)
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
                                        <td>
                                            <x-button>
                                                Acknowledge
                                            </x-button>
                                        </td>
                                    </tr>
                                @endforeach
                            </x-slot>
                        </x-table.container>
                    </div>
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
        let currentTab = 'pending';

        function showTab(tab) {
            if (tab === currentTab) return;

            const pendingBtn = document.getElementById('pending-tab-btn');
            const historyBtn = document.getElementById('history-tab-btn');
            const pendingContent = document.getElementById('pending-content');
            const historyContent = document.getElementById('history-content');
            const pendingText = document.getElementById('pending-tab-text');
            const historyText = document.getElementById('history-tab-text');

            if (tab === 'pending') {
                historyText.classList.remove('tab-btn-text-active');
                historyText.classList.add('tab-btn-text-inactive');
                pendingText.classList.remove('tab-btn-text-inactive');
                pendingText.classList.add('tab-btn-text-active');
            } else {
                pendingText.classList.remove('tab-btn-text-active');
                pendingText.classList.add('tab-btn-text-inactive');
                historyText.classList.remove('tab-btn-text-inactive');
                historyText.classList.add('tab-btn-text-active');
            }

            if (tab === 'pending') {
                pendingBtn.classList.add('text-lime-600', 'border-lime-600');
                pendingBtn.classList.remove('text-lime-700', 'border-transparent');
                historyBtn.classList.add('text-lime-700', 'border-transparent');
                historyBtn.classList.remove('text-lime-600', 'border-lime-600');
            } else {
                historyBtn.classList.add('text-lime-600', 'border-lime-600');
                historyBtn.classList.remove('text-lime-700', 'border-transparent');
                pendingBtn.classList.add('text-lime-700', 'border-transparent');
                pendingBtn.classList.remove('text-lime-600', 'border-lime-600');
            }

            let outgoing, incoming, direction;
            if (tab === 'pending') {
                outgoing = historyContent;
                incoming = pendingContent;
                direction = -1;
            } else {
                outgoing = pendingContent;
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
            document.getElementById('pending-tab-text').classList.add('tab-btn-text-active');
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
    </style>
</x-app-layout>
