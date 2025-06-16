@props(['caption', 'value', 'footer' => false, 'footerCaption' => '', 'footerDate' => '', 'wide' => false, 'headerActions' => null])

<div {{ $attributes->merge(['class' => 'bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 ' . ($wide ? 'w-full' : '')]) }}>
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ $caption }}
            </h3>
            @isset($headerActions)
                <div class="ml-auto">
                    {{ $headerActions }}
                </div>
            @endisset
        </div>
        @if ($value !== '') {{-- Only display value if it's not empty --}}
            <div class="text-3xl font-bold text-gray-900">
                {{ $value }}
            </div>
        @endif

        @if (isset($footer) && $footer)
            <div class="mt-4 text-sm text-gray-500">
                {{ $footerCaption ?? '' }}: <span class="font-semibold">{{ $footerDate ?? '' }}</span>
            </div>
        @endif
        {{ $slot }}
    </div>
</div>
