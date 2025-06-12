@props(['variant' => 'text', 'uppercase' => true, 'disabled' => false])

@php
    $case = $uppercase ? 'text-xs uppercase tracking-widest' : 'text-sm';
    $baseClass =
        $case .
        ' ' .
        'inline-flex text-center items-center gap-x-1 justify-center px-4 py-2 border rounded-md font-semibold focus:outline-hidden focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150';

    $variants = [
        'primary' =>
            'bg-lime-600 border-transparent text-white hover:bg-lime-700 focus:bg-lime-700 focus:ring-lime-700 active:bg-lime-800',
        'clean' => 'bg-white border-gray-300 text-gray-700 shadow-xs hover:bg-gray-50 focus:ring-lime-600',
        'dark' =>
            'bg-gray-800 border-transparent text-white hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:ring-lime-600',
        'danger' => 'bg-red-600 border-transparent text-white hover:bg-red-500 active:bg-red-700 focus:ring-red-500',
        'text' =>
            'text-sm text-lime-600 hover:underline hover:text-lime-700 focus:underline focus:text-lime-700 bg-transparent focus:outline-hidden ',
    ];

    $isPlainText = $variant === 'text';

    $buttonClass = $isPlainText ? $variants['text'] : $baseClass . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

@if ($disabled)
    <span {{ $attributes->merge(['class' => $buttonClass . ' opacity-60']) }}>
        {{ $slot }}
    </span>
@else
    <a {{ $attributes->merge(['class' => $buttonClass]) }}>
        {{ $slot }}
    </a>
@endif
