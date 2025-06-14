@props([
    'caption' => '',
    'value' => '',
    'footer' => false, // set to true for cards with footer (e.g. Last Due Date)
    'footerCaption' => '',
    'footerDate' => '',
    'wide' => false, // set to true for Quick Access Button layout
])

@php
    // All cards will now inherently take 'w-full' of their parent column's width.
    // The 'wide' prop can still be used for other styling, like button alignment.
    $widthClass = 'w-full'; // All cards will now take the full width of their containing column.
@endphp

<div {{ $attributes->merge(['class' => "bg-white shadow-lg rounded-3xl p-6 w-full {$widthClass} text-center mb-6 transition-all"]) }}>
    <h1 class="text-gray-800 text-lg font-bold mb-1">{{ $caption }}</h1>
    <hr class="border-b-zinc-800 mb-2" />
    <h3 class="text-2xl font-bold text-gray-800 mb-1">{{ $value }}</h3>

    @if ($footer)
        <div class="flex justify-between text-left mt-4 text-sm text-gray-500">
            <h6 class="font-medium">{{ $footerCaption }}:</h6>
            <h6 class="font-semibold">{{ $footerDate }}</h6>
        </div>
    @endif

    {{-- Removed 'flex' and 'justify-center' from this div to allow the slot's content to control its own layout --}}
    <div class="pt-2">
        {{ $slot }} <!-- for Quick Access Buttons Layout -->
    </div>
</div>
