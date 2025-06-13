<x-app-layout title="Chat with {{ $otherParticipant->name ?? 'Unknown User' }}">
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('messages.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Chat with {{ $otherParticipant->name ?? 'Unknown User' }}
            </h2>
        </div>
    </x-slot>

    @php
        $pictureBanner = $otherParticipant->profile_picture
            ? Storage::url($otherParticipant->profile_picture)
            : Vite::asset('resources/assets/images/default_profile.png');
    @endphp

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg border-l-4 border-lime-800 mb-8">
            <!-- Messages Container -->
            <div class="p-4 flex gap-2 items-center text-lg font-bold border-b border-gray-200 rounded-b-md">
                <x-a href="{{ route('messages.index') }}">
                    <i class="ph-bold ph-arrow-left text-lg"></i>
                </x-a>
                <img class="size-8 aspect-square rounded-full" src="{{ $pictureBanner }}" />
                {{ $otherParticipant->name ?? 'Unknown User' }}
            </div>
            <div id="messages-container" class="h-[calc(100vh-15rem)] overflow-y-auto p-4 space-y-4 border-b">
                @foreach ($messages as $message)
                    @empty(!$message->content)
                        <div
                            class="message {{ $message->user_id === auth()->id() ? 'flex justify-end' : 'flex justify-start' }}">
                            @if ($message->user_id === auth()->id())
                                @php
                                    $profilePicture = auth()->user()->profile_picture
                                        ? Storage::url(auth()->user()->profile_picture)
                                        : Vite::asset('resources/assets/images/default_profile.png');
                                @endphp
                                <div class="flex items-start gap-2.5 flex-row-reverse">
                                    <img class="aspect-square w-8 h-8 rounded-full" src="{{ $profilePicture }}"
                                        alt="{{ auth()->user()->name }} avatar">
                                    <div
                                        class="flex flex-col w-full max-w-[320px] leading-1.5 p-4 border-gray-200 bg-lime-600 rounded-s-xl rounded-es-xl">
                                        <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                            <span class="text-sm font-semibold text-white">You</span>
                                            <span
                                                class="text-sm font-normal text-lime-100">{{ $message->created_at->format('g:i A') }}</span>
                                        </div>
                                        <p class="text-sm font-normal py-2.5 text-white">{{ $message->content }}</p>
                                    </div>
                                </div>
                            @else
                                @php
                                    $recipientProfilePicture = $message->user->profile_picture
                                        ? Storage::url($message->user->profile_picture)
                                        : Vite::asset('resources/assets/images/default_profile.png');
                                @endphp
                                <div class="flex items-start gap-2.5">
                                    <img class="aspect-square w-8 h-8 rounded-full" src="{{ $recipientProfilePicture }}"
                                        alt="{{ $message->user->name ?? 'User' }} avatar">
                                    <div
                                        class="flex flex-col w-full max-w-[320px] leading-1.5 p-4 border-gray-200 bg-gray-100 rounded-e-xl rounded-es-xl">
                                        <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                            <span
                                                class="text-sm font-semibold text-gray-900">{{ $message->user->name ?? 'Unknown User' }}</span>
                                            <span
                                                class="text-sm font-normal text-gray-500">{{ $message->created_at->format('g:i A') }}</span>
                                        </div>
                                        <p class="text-sm font-normal py-2.5 text-gray-900">{{ $message->content }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endempty
                @endforeach
            </div>

            <!-- Message Input Form -->
            <form id="message-form" class="p-4">
                @csrf
                <div class="flex flex-col w-full gap-2 sm:flex-row">
                    <x-input.text type="text" id="message-input" name="content" placeholder="Type your message..." />
                    <x-button type="submit"><i class="ph-bold ph-paper-plane-tilt text-lg"></i></x-button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const conversationId = {{ $conversation->id }};
                const currentUserId = {{ auth()->id() }};

                window.Echo.private(`conversation.${conversationId}`)
                    .listen('MessageSent', (e) => {
                        addMessageToChat(e.message);
                    });

                document.getElementById('message-form').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const messageInput = document.getElementById('message-input');
                    const content = messageInput.value.trim();
                    if (!content) return;

                    fetch(`{{ route('messages.store', $conversation) }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                content: content
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                messageInput.value = '';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Failed to send message. Please try again.');
                        });
                });

                function addMessageToChat(message) {
                    const content = message.content?.trim();
                    if (!content) return;

                    const messagesContainer = document.getElementById('messages-container');
                    const isCurrentUser = message.user_id === currentUserId;

                    const messageDiv = document.createElement('div');
                    messageDiv.className = `message ${isCurrentUser ? 'flex justify-end' : 'flex justify-start'}`;

                    if (isCurrentUser) {
                        messageDiv.innerHTML = `
                            <div class="flex items-start gap-2.5 flex-row-reverse">
                                <img class="aspect-square w-8 h-8 rounded-full" src="${message.user.profile_picture}" alt="${message.user.name} avatar">
                                <div class="flex flex-col w-full max-w-[320px] leading-1.5 p-4 border-gray-200 bg-lime-600 rounded-s-xl rounded-es-xl">
                                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                        <span class="text-sm font-semibold text-white">You</span>
                                        <span class="text-sm font-normal text-lime-100">${message.created_at}</span>
                                    </div>
                                    <p class="text-sm font-normal py-2.5 text-white">${escapeHtml(message.content)}</p>
                                </div>
                            </div>
                        `;
                    } else {
                        messageDiv.innerHTML = `
                            <div class="flex items-start gap-2.5">
                                <img class="aspect-square w-8 h-8 rounded-full" src="${message.user.profile_picture}" alt="${message.user.name} avatar">
                                <div class="flex flex-col w-full max-w-[320px] leading-1.5 p-4 border-gray-200 bg-gray-100 rounded-e-xl rounded-es-xl">
                                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                        <span class="text-sm font-semibold text-gray-900">${message.user.name}</span>
                                        <span class="text-sm font-normal text-gray-500">${message.created_at}</span>
                                    </div>
                                    <p class="text-sm font-normal py-2.5 text-gray-900">${escapeHtml(message.content)}</p>
                                </div>
                            </div>
                        `;
                    }

                    messagesContainer.appendChild(messageDiv);
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }

                function escapeHtml(text) {
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                }

                document.getElementById('message-input').focus();
                const messagesContainer = document.getElementById('messages-container');
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            });
        </script>
    @endpush
</x-app-layout>
