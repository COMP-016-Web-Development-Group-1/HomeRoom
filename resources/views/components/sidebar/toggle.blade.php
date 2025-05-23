{{-- resources/views/components/sidebar/toggle.blade.php --}}
<button class="fixed right-4 top-4 z-20 rounded-full bg-black p-4 md:hidden text-neutral-100"
    x-on:click="showSidebar = ! showSidebar">
    <i x-show="showSidebar" class="ph ph-x-bold text-lg"></i>
    <i x-show="! showSidebar" class="ph ph-list-bold text-lg"></i>
    <span class="sr-only">toggle sidebar</span>
</button>
