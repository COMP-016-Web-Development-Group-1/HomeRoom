@props(['icon', 'title', 'badge' => null])

<div x-data="{ isExpanded: false }" class="flex flex-col">
    {{-- Collapsible Header --}}
    <button type="button" x-on:click="isExpanded = !isExpanded"
        class="flex items-center gap-x-2 w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-800 hover:text-gray-900 hover:bg-gray-100 hover:border-gray-400 focus:outline-none focus:text-gray-900 focus:bg-gray-100 focus:border-gray-400 transition duration-150 ease-in-out"
        {{-- :class="isExpanded ? 'text-blue-700 bg-blue-50 border-blue-400' : ''" --}}>
        <i class="ph ph-{{ $icon }} text-lg"></i>
        <span>{{ $title }}</span>

        @if ($badge)
            <span
                class="ms-auto inline-flex items-center justify-center px-2 py-0.5 text-sm font-semibold text-blue-900 bg-blue-200 rounded-full">
                {{ $badge }}
            </span>
        @else
            <i class="ph-bold ph-caret-down text-lg ms-auto transition-transform"
                :class="isExpanded ? 'rotate-180' : ''"></i>
        @endif
    </button>

    <ul x-cloak x-collapse x-show="isExpanded" class="flex flex-col ms-4 border-s border-gray-200 mt-1">
        {{ $slot }}
    </ul>
</div>
