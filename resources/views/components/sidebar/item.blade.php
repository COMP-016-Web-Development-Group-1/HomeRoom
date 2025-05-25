@props(['icon', 'title', 'active' => false, 'badge' => null])

@php
    $classes = $active
        ? 'flex items-center gap-x-2 w-full ps-3 pe-4 py-2 border-l-4 border-blue-400 text-start text-base font-medium text-blue-700 bg-blue-50 focus:outline-none focus:text-blue-800 focus:bg-blue-100 focus:border-blue-700 transition duration-150 ease-in-out'
        : 'flex items-center gap-x-2 w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-800 hover:text-gray-900 hover:bg-gray-100 hover:border-gray-400 focus:outline-none focus:text-gray-900 focus:bg-gray-100 focus:border-gray-400 transition duration-150 ease-in-out';

    $badgeClasses = $active ? 'text-blue-800 bg-blue-200' : 'text-blue-700 bg-blue-100';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <i class="ph{{ $active ? '-fill' : '-bold' }} ph-{{ $icon }} text-lg"></i>
    <span>{{ $title }}</span>

    @if ($badge)
        <span
            class="ml-auto inline-flex items-center justify-center min-w-[1.25rem] h-5 px-2 text-xs font-semibold rounded-full {{ $badgeClasses }}">
            {{ $badge }}
        </span>
    @endif
</a>
