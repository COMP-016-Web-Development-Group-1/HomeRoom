<x-app-layout title="Messages">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Messages
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="text-gray-900">
            <div class="bg-white shadow rounded-lg">
                @if ($conversations->isEmpty())
                    <div class="p-8 text-center">
                        <div class="text-gray-500 mb-4">
                            <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.955 8.955 0 01-4.126-.98L3 21l1.98-5.874A8.955 8.955 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No conversations yet</h3>
                        <p class="text-gray-500">Start a conversation to get in touch with other users.</p>
                    </div>
                @else
                    <div class="divide-y">
                        @foreach ($conversations as $conversation)
                            @php
                                $otherParticipant = $conversation->getOtherParticipant(auth()->id());
                                $latestMessage = $conversation->latestMessage->first();
                            @endphp
                            <a href="{{ route('messages.show', $conversation) }}"
                                class="block p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">
                                                {{ strtoupper(substr($otherParticipant->name ?? 'U', 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $otherParticipant->name ?? 'Unknown User' }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $latestMessage?->created_at?->diffForHumans() }}
                                            </p>
                                        </div>
                                        @if ($latestMessage)
                                            <p class="text-sm text-gray-500 truncate">
                                                {{ Str::limit($latestMessage->content, 50) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
