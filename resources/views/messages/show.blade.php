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

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg">
            <!-- Messages Container -->
            <div id="messages-container" class="h-96 overflow-y-auto p-4 space-y-4 border-b">
                @foreach ($messages as $message)
                    <div
                        class="message flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div
                            class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message->user_id === auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-900' }}">
                            <p class="text-sm">{{ $message->content }}</p>
                            <p
                                class="text-xs mt-1 {{ $message->user_id === auth()->id() ? 'text-blue-100' : 'text-gray-500' }}">
                                {{ $message->created_at->format('M j, Y g:i A') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Message Input Form -->
            <form id="message-form" class="p-4">
                @csrf
                <div class="flex space-x-3">
                    <input type="text" id="message-input" name="content" placeholder="Type your message..."
                        class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                    <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Send
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {

                // Laravel Echo setup
                const conversationId = {{ $conversation->id }};
                const currentUserId = {{ auth()->id() }};

                // Listen for new messages
                window.Echo.private(`conversation.${conversationId}`)
                    .listen('MessageSent', (e) => {
                        addMessageToChat(e.message);
                    });

                // Handle form submission
                document.getElementById('message-form').addEventListener('submit', function(e) {
                    e.preventDefault();

                    const messageInput = document.getElementById('message-input');
                    const content = messageInput.value.trim();

                    if (!content) return;

                    // Send message via AJAX
                    fetch(`{{ route('messages.store', $conversation) }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute(
                                        'content'),
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
                                // Message will be added via Echo broadcast
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Failed to send message. Please try again.');
                        });
                });

                function addMessageToChat(message) {
                    const messagesContainer = document.getElementById('messages-container');
                    const isCurrentUser = message.user_id === currentUserId;

                    const messageDiv = document.createElement('div');
                    messageDiv.className = `message flex ${isCurrentUser ? 'justify-end' : 'justify-start'}`;

                    messageDiv.innerHTML = `
                <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg ${isCurrentUser ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-900'}">
                    <p class="text-sm">${escapeHtml(message.content)}</p>
                    <p class="text-xs mt-1 ${isCurrentUser ? 'text-blue-100' : 'text-gray-500'}">
                        ${message.created_at}
                    </p>
                </div>
            `;

                    messagesContainer.appendChild(messageDiv);
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }

                function escapeHtml(text) {
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                }

                // Auto-scroll to bottom on page load
                document.addEventListener('DOMContentLoaded', function() {
                    const messagesContainer = document.getElementById('messages-container');
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                });

                // Auto-focus message input
                document.getElementById('message-input').focus();
            })
        </script>
    @endpush
</x-app-layout>
