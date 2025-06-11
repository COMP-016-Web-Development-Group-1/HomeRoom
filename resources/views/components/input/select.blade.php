@props([
    'options' => [],
    'selected' => null,
])

<select
    {{ $attributes->merge(['class' => 'bg-gray-50 border border-gray-300 text-gray-900 rounded-md focus:ring-lime-600 focus:border-lime-600 block w-full']) }}>

    {{-- Empty initial option --}}
    <option value="" disabled {{ $selected === null ? 'selected' : '' }}>Select a type</option>

    {{-- Loop through the options --}}
    @foreach ($options as $value => $label)
        <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }}>
            {{ $label }}
        </option>
    @endforeach
</select>
