{{-- resources/views/components/sidebar.blade.php --}}
<div x-data="{ showSidebar: false }" class="relative flex w-full flex-col md:flex-row">
    <a class="sr-only" href="#main-content">skip to main content</a>

    {{-- Dark overlay --}}
    <div x-cloak x-show="showSidebar" class="fixed inset-0 z-10 bg-neutral-950/10 backdrop-blur-xs md:hidden"
        aria-hidden="true" x-on:click="showSidebar = false" x-transition.opacity></div>

    <nav x-cloak
        class="fixed left-0 z-20 flex h-svh w-60 shrink-0 flex-col border-r border-neutral-300 bg-neutral-50 p-4 transition-transform duration-300 md:w-64 md:translate-x-0 md:relative"
        x-bind:class="showSidebar ? 'translate-x-0' : '-translate-x-60'" aria-label="sidebar navigation">

        {{-- Header Section --}}
        <div class="pb-4">
            @if (isset($header))
                {{ $header }}
            @endif

            <hr />
        </div>

        {{-- Search --}}
        <div class="relative my-4 flex w-full max-w-xs flex-col gap-1 text-neutral-600">
            <i
                class="ph-bold ph-magnifying-glass absolute left-2 top-1/2 text-lg -translate-y-1/2 text-neutral-600/50"></i>
            <input type="search"
                class="w-full border border-neutral-300 rounded-sm bg-white px-2 py-1.5 pl-9 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black disabled:cursor-not-allowed disabled:opacity-75"
                name="search" aria-label="Search" placeholder="Search" />
        </div>

        {{-- Menu Items --}}
        <div class="flex flex-col gap-2 overflow-y-auto pb-6">
            {{ $slot }}
        </div>

        {{-- Add this footer section --}}
        <div class="flex items-center justify-end mt-auto border-t border-neutral-300 pt-4">
            {{ $footer }}
        </div>
    </nav>

    {{-- Main Content --}}
    <div id="main-content" class="h-svh w-full overflow-y-auto p-4 bg-white">
        {{ $main }}
    </div>

    {{-- Toggle Button --}}
    <x-sidebar.toggle />
</div>
