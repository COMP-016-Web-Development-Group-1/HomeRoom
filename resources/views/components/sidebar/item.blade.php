{{-- resources/views/components/sidebar/item.blade.php --}}
@props(['icon', 'title', 'active' => false])

<a
    {{ $attributes->merge([
        'class' =>
            'flex items-center rounded-sm gap-2 px-2 py-1.5 text-sm font-medium text-neutral-600 underline-offset-2 hover:bg-black/5 hover:text-neutral-900 focus-visible:underline focus:outline-hidden',
    ]) }}>
    <i class="ph ph-{{ $icon }} text-lg"></i>
    <span>{{ $title }}</span>
    {{ $slot }}
</a>
