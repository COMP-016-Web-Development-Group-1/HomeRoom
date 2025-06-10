<x-guest-layout title="Add New Property">
    <div class="text-center p-4">
        <h1 class="font-bold text-xl"><i class="ph-bold ph-plus-circle"></i> Add New Property</h1>
    </div>

    <form method="POST" action="{{ route('property.store') }}">
        @csrf

        <div class="mb-2 p-2">
            <x-input.label for="name" :required="true">Property Name</x-input.label>
            <x-input.text id="name" type="text" name="name" :value="old('name', $data['name'] ?? '')" autofocus />
            <x-input.error for="name" />
        </div>

        <div class="mb-2 p-2">
            <x-input.label for="type" :required="true">Property Type</x-input.label>
            <x-input.select id="type" name="type"
                :options="[
                    'apartment' => 'Apartment',
                    'house' => 'House',
                    'dorm' => 'Dorm',
                    'condominium' => 'Condominium', ]"
                :selected="old('type')"
                :value="old('name', $data['type'] ?? '')" />
            <x-input.error for="type" />
        </div>

        <div class="mb-2 p-2">
            <x-input.label for="address" :required="true">Property Address</x-input.label>
            <x-input.text id="address" type="text" name="address" :value="old('name', $data['address'] ?? '')" />
            <p class="text-xs text-center">Building/Unit/House Number, Street Name, Barangay, City/Municipality, Province, ZIP Code</p>
            <x-input.error for="address" />
        </div>

        <div class="mb-2 p-2">
            <x-input.label for="description" :required="true">Description</x-input.label>
            <x-input.textarea id="description" name="description" rows="4" :value="old('name', $data['description'] ?? '')" />
            <x-input.error for="address" />
        </div>

        <div class="flex items-end justify-between gap-x-3 mb-4 mt-4">
            <x-button id="cancelBtn" variant="clean" >Cancel</x-button>
            <x-button type="submit" variant="primary">Submit</x-button>
        </div>
    </form>
</x-guest-layout>

<script>
    document.getElementById('cancelBtn').addEventListener('click', function (e) {
        e.preventDefault(); // Prevent form submission
        window.history.back(); // Navigate to previous page
    });
</script>
