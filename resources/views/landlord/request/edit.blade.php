<x-app-layout title="Edit Announcement">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Announcement
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-8">
            <form method="POST" action="{{ route('request.update', $request) }}" x-data="{ type: '{{ old('type', $request->type) }}' }">
                @csrf
                @method('PUT')

                <!-- Type Dropdown -->
                <div class="mb-6">
                    <x-input.label for="type" required>Change Status</x-input.label>
                    <x-input.select id="type" name="type" x-model="type" required>
                        <x-input.option value="status" :selected="old('type', $request->type) == 'pending'">Pending</x-input.option>
                        <x-input.option value="status" :selected="old('type', $request->type) == 'in_progress'">In Progress</x-input.option>
                        <x-input.option value="status" :selected="old('type', $request->type) == 'resolved'">Resolved</x-input.option>
                        <x-input.option value="status" :selected="old('type', $request->type) == 'rejected'">Rejected</x-input.option>
                    </x-input.select>
                    <x-input.error for="type" />
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-center gap-x-12 gap-y-3 flex-wrap">
                    <x-a variant="clean" href="{{ route('landlord.request.index') }}">
                        Cancel
                    </x-a>
                    <x-button type="submit">
                        Update Request
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
