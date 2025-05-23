{{-- resources/views/components/sidebar/collapsible.blade.php --}}
@props(['icon', 'title'])

<div x-data="{ isExpanded: false }" class="flex flex-col">
    <button type="button" x-on:click="isExpanded = ! isExpanded"
        class="flex items-center justify-between rounded-sm gap-2 px-2 py-1.5 text-sm font-medium underline-offset-2 focus:outline-hidden focus-visible:underline"
        :class="isExpanded ? 'text-neutral-900 bg-black/10' : 'text-neutral-600 hover:bg-black/5 hover:text-neutral-900'">
        <i class="ph ph-{{ $icon }} text-lg"></i>
        <span class="mr-auto text-left">{{ $title }}</span>
        <i class="ph ph-caret-down-bold text-lg transition-transform" :class="isExpanded ? 'rotate-180' : ''"></i>
    </button>

    <ul x-cloak x-collapse x-show="isExpanded" class="pl-4">
        {{ $slot }}
    </ul>
</div>
