@props(['id', 'disabled' => false, 'help' => null])

@php
    $helpId = $id . '_help';
@endphp

<input type="file" {{ $attributes->merge() }}
    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
            focus:ring-blue-500 focus:border-blue-500 block w-full focus:outline-none
            file:cursor-pointer cursor-pointer file:border-0 file:py-3 file:px-4 file:rounded file:font-lexend file:text-xs file:mr-3
            file:bg-gray-800 file:hover:bg-gray-700 file:text-white"
    @disabled($disabled) aria-describedby="{{ $helpId }}" />

@if ($help)
    <p id="{{ $helpId }}" class="text-xs text-gray-500 mt-1">{{ $help }}</p>
@endif
