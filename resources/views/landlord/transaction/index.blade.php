<x-app-layout title="Transactions">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Transactions
        </h2>
    </x-slot>
    <div
        x-data="{
            showAcknowledgeModal: false,
            acknowledgeId: null,
            activeTab: 'pending',
            openAcknowledgeModal(id) {
                this.acknowledgeId = id;
                this.showAcknowledgeModal = true;
            },
            closeAcknowledgeModal() {
                this.showAcknowledgeModal = false;
                this.acknowledgeId = null;
            },
            submitAcknowledgeForm() {
                if (this.acknowledgeId !== null) {
                    this.$refs['acknowledgeForm' + this.acknowledgeId].submit();
                }
            },
            switchTab(tab) {
                this.activeTab = tab;
            }
        }"
        class="max-w-(--breakpoint-2xl) mx-auto sm:px-6 lg:px-8 relative"
    >
        <div class="bg-white shadow-xs sm:rounded-lg main-blur-area transition-all duration-200 min-h-[760px] flex flex-col" :class="showAcknowledgeModal ? 'blur-md pointer-events-none select-none' : ''">
            <div class="p-6 text-gray-900 flex flex-col flex-1">
                <!-- Added H1 for Transaction Tab -->
                <h1 class="text-3xl font-bold mb-8 text-lime-800">
                    Manage Transactions
                </h1>

                <div class="mb-4 flex space-x-8">
                    <button
                        id="pending-tab-btn"
                        type="button"
                        :class="activeTab === 'pending'
                            ? 'tab-btn text-lime-600 font-semibold border-b-2 border-lime-600'
                            : 'tab-btn text-lime-700 font-semibold border-b-2 border-transparent hover:text-lime-600'"
                        @click="switchTab('pending')"
                    >
                        <span id="pending-tab-text"
                              :class="activeTab === 'pending' ? 'tab-btn-text tab-btn-text-active' : 'tab-btn-text tab-btn-text-inactive'"
                              class="transition-all duration-500"
                        >Pending Payments</span>
                    </button>
                    <button
                        id="history-tab-btn"
                        type="button"
                        :class="activeTab === 'history'
                            ? 'tab-btn text-lime-600 font-semibold border-b-2 border-lime-600'
                            : 'tab-btn text-lime-700 font-semibold border-b-2 border-transparent hover:text-lime-600'"
                        @click="switchTab('history')"
                    >
                        <span id="history-tab-text"
                              :class="activeTab === 'history' ? 'tab-btn-text tab-btn-text-active' : 'tab-btn-text tab-btn-text-inactive'"
                              class="transition-all duration-500"
                        >History</span>
                    </button>
                </div>
                <div class="relative flex-1">
                    <!-- Pending Tab -->
                    <div
                        class="tab-pane transition-transform duration-500 ease-in-out"
                        :class="{
                            'block': activeTab === 'pending',
                            'hidden': activeTab !== 'pending'
                        }"
                        style="transform: translateX(0%);"
                    >
                        <x-table.container id="pending-payments-table">
                            <x-slot name="header">
                                <th class="bg-lime-700 text-white">Property</th>
                                <th class="bg-lime-700 text-white">Room #</th>
                                <th class="bg-lime-700 text-white">Bill Due Date</th>
                                <th class="bg-lime-700 text-white">Amount</th>
                                <th class="bg-lime-700 text-white">Payment Method</th>
                                <th class="bg-lime-700 text-white">Status</th>
                                <th class="bg-lime-700 text-white">Reference #</th>
                                <th class="bg-lime-700 text-white">Proof</th>
                                <th class="bg-lime-700 text-white">Actions</th>
                            </x-slot>
                            <x-slot name="body">
                                @foreach($pendingTransactions as $transaction)
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
                                        <td>
                                            <form method="POST"
                                                  action="{{ route('transaction.update', $transaction->id) }}"
                                                  x-ref="acknowledgeForm{{ $transaction->id }}"
                                                  style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="action" value="acknowledge">
                                                <x-button type="button"
                                                          variant="success"
                                                          @click="openAcknowledgeModal({{ $transaction->id }})">
                                                    Acknowledge
                                                </x-button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </x-slot>
                        </x-table.container>
                    </div>
                    <!-- History Tab -->
                    <div
                        class="tab-pane transition-transform duration-500 ease-in-out"
                        :class="{
                            'block': activeTab === 'history',
                            'hidden': activeTab !== 'history'
                        }"
                        style="transform: translateX(0%);"
                    >
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
                                @foreach($historyTransactions as $transaction)
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

        <!-- Custom Confirm Modal, only the modal is NOT blurred -->
        <div
            x-show="showAcknowledgeModal"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center"
            style="backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);"
        >
            <div class="relative bg-white rounded-lg shadow-lg p-8 max-w-md w-full z-10 border border-lime-600">
                <div class="text-center mb-6">
                    <h3 class="text-xl font-bold mb-4">Acknowledge Payment</h3>
                    <p class="mb-6">Are you sure you want to acknowledge this transaction?</p>
                </div>
                <div class="flex justify-center space-x-4">
                    <button type="button"
                            class="px-4 py-2 rounded bg-gray-300 text-gray-800 hover:bg-gray-400"
                            @click="closeAcknowledgeModal()">
                        Cancel
                    </button>
                    <button type="button"
                            class="px-4 py-2 rounded bg-lime-600 text-white hover:bg-lime-700"
                            @click="submitAcknowledgeForm()">
                        Yes, Acknowledge
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
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
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
