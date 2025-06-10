{{-- resources/views/announcement/edit.blade.php --}}
<x-app-layout title="Edit Announcement">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Announcement
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-8">
            <form method="POST" action="{{ route('announcement.update', $announcement) }}" x-data="{ type: '{{ old('type', $announcement->type) }}' }">
                @csrf
                @method('PUT')

                <!-- Type Dropdown -->
                <div class="mb-6">
                    <x-input.label for="type" required>Type</x-input.label>
                    <x-input.select id="type" name="type" x-model="type" required>
                        <x-input.option value="system" :selected="old('type', $announcement->type) == 'system'">System</x-input.option>
                        <x-input.option value="property" :selected="old('type', $announcement->type) == 'property'">Property</x-input.option>
                        <x-input.option value="room" :selected="old('type', $announcement->type) == 'room'">Room</x-input.option>
                    </x-input.select>
                    <x-input.error for="type" />
                </div>

                <!-- Property Dropdown (shown for property/room) -->
                <div class="mb-6" x-show="type == 'property' || type == 'room'" x-cloak>
                    <x-input.label for="property_id" required>Property</x-input.label>
                    <x-input.select id="property_id" name="property_id">
                        <x-input.option value="" disabled selected hidden>-- Please select a property
                            --</x-input.option>
                        @foreach ($properties as $property)
                            <x-input.option value="{{ $property->id }}" :selected="old('property_id', $announcement->property_id) == $property->id">
                                {{ $property->name }}
                            </x-input.option>
                        @endforeach
                    </x-input.select>
                    <x-input.error for="property_id" />
                </div>

                <!-- Room Dropdown (shown for room only) -->
                <div class="mb-6" x-show="type == 'room'" x-cloak>
                    <x-input.label for="room_id" required>Room</x-input.label>
                    <x-input.select id="room_id" name="room_id">
                        <x-input.option value="" disabled selected hidden>-- Please select a room
                            --</x-input.option>
                        @foreach ($rooms as $room)
                            <x-input.option value="{{ $room->id }}" :selected="old('room_id', $announcement->room_id) == $room->id">
                                {{ $room->name }} ({{ $room->property->name }})
                            </x-input.option>
                        @endforeach
                    </x-input.select>
                    <x-input.error for="room_id" />
                </div>

                <!-- Title -->
                <div class="mb-6">
                    <x-input.label for="title" required>Title</x-input.label>
                    <x-input.text id="title" type="text" name="title" :value="old('title', $announcement->title)" autofocus />
                    <x-input.error for="title" />
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <x-input.label for="description" required>Description</x-input.label>
                    <x-input.textarea id="description" name="description"
                        rows="6">{{ old('description', $announcement->description) }}</x-input.textarea>
                    <x-input.error for="description" />
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-center gap-x-12 gap-y-3 flex-wrap">
                    <x-a variant="clean" href="{{ route('announcement.index') }}">
                        Cancel
                    </x-a>
                    <x-button type="submit">
                        Update Announcement
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
