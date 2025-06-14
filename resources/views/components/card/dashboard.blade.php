@props([
    'caption' => '',
    'value' => '',
    'footer' => false, // set to true for cards with footer (e.g. Last Due Date)
    'footerCaption' => '',
    'footerDate' => '',
    'wide' => false, // set to true for Quick Access Button layout
])

@php
    $widthClass = $wide ? 'max-w-xl' : 'max-w-xs';
@endphp

<div {{ $attributes->merge(['class' => "bg-white shadow-lg rounded-3xl p-6 w-full {$widthClass} text-center mb-6 transition-all"]) }}>
    <h1 class="text-gray-800 text-sm font-medium mb-1">{{ $caption }}</h1>
    <hr class="border-b-zinc-800 mb-2" />
    <h3 class="text-2xl font-bold text-gray-800 mb-1">{{ $value }}</h3>

    @if ($footer)
        <div class="flex justify-between text-left mt-4 text-sm text-gray-500">
            <h6 class="font-medium">{{ $footerCaption }}:</h6>
            <h6 class="font-semibold">{{ $footerDate }}</h6>
        </div>
    @endif

    <div class="flex justify-between pt-2">
        {{ $slot }} <!-- for Quick Access Buttons Layout -->
    </div>
</div>
