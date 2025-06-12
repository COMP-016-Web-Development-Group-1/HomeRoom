{{-- resources/views/landlord/request/edit.blade.php (Adjust this file) --}}

<x-app-layout title="Edit Request Status">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Request Status
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-8">
            <form method="POST" action="{{ route('request.update', $request) }}">
                @csrf
                @method('PUT')

                {{-- Display Title and Description as read-only for context --}}
                <div class="mb-6">
                    <x-input.label for="title">Title</x-input.label>
                    <x-input.text id="title" type="text" name="title" :value="$request->title" disabled />
                </div>

                <div class="mb-6">
                    <x-input.label for="description">Description</x-input.label>
                    <x-input.textarea id="description" name="description" rows="6" disabled>{{ $request->description }}</x-input.textarea>
                </div>

                <div class="mb-6">
                    <x-input.label for="status" required>Status</x-input.label>
                    <x-input.select id="status" name="status">
                        <option value="pending" {{ old('status', $request->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ old('status', $request->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ old('status', $request->status) == 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="rejected" {{ old('status', $request->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </x-input.select>
                    <x-input.error for="status" />
                </div>

                <div class="flex items-center justify-center gap-x-12 gap-y-3 flex-wrap">
                    {{-- THIS IS THE CRITICAL CHANGE --}}
                    <x-a variant="clean" href="{{ route('request.index') }}">
                        Cancel
                    </x-a>
                    <x-button type="submit">
                        Update Status
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
