@props(['variant' => 'text'])

@php
    $baseClass =
        'inline-flex items-center gap-x-1 justify-center px-4 py-2 border rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150';

    $variants = [
        'primary' =>
            'bg-blue-500 border-transparent text-white hover:bg-blue-600 focus:bg-blue-600 focus:ring-blue-600 active:bg-blue-700',
        'clean' => 'bg-white border-gray-300 text-gray-700 shadow-sm hover:bg-gray-50 focus:ring-blue-500',
        'dark' =>
            'bg-gray-800 border-transparent text-white hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:ring-blue-500',
        'danger' => 'bg-red-600 border-transparent text-white hover:bg-red-500 active:bg-red-700 focus:ring-red-500',
        'text' =>
            'text-sm text-blue-500 hover:underline hover:text-blue-600 focus:underline focus:text-blue-600 bg-transparent focus:outline-none ',
    ];

    $isPlainText = $variant === 'text';

    $buttonClass = $isPlainText ? $variants['text'] : $baseClass . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

<a {{ $attributes->merge(['class' => $buttonClass]) }}>
    {{ $slot }}
</a>
