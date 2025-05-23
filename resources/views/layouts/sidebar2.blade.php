<x-sidebar.container>
    <x-slot:header>
        <div class="flex items-center gap-2 px-2 py-1">
            <i class="ph-fill ph-house text-3xl text-blue-600"></i>
            <span class="text-2xl font-bold">HomeRoom</span>
        </div>
    </x-slot:header>

    <x-sidebar.item icon="gauge" title="Dashboard" href="#" />

    <x-sidebar.collapsible icon="users" title="User Management">
        <x-sidebar.item icon="user" title="Users" href="#" />
        <x-sidebar.item icon="lock-key" title="Permissions" href="#" />
        <x-sidebar.item icon="clock-counter-clockwise" title="Activity Log" href="#" />
    </x-sidebar.collapsible>

    <x-sidebar.collapsible icon="package" title="Products">
        <x-sidebar.item icon="boxes" title="All Products" href="{{ route('register') }}" />
        <x-sidebar.item icon="warehouse" title="Inventory" href="#" />
        <x-sidebar.item icon="star" title="Reviews" href="#" />
    </x-sidebar.collapsible>

    <x-sidebar.item icon="gear" title="Settings" href="#" />

    <x-slot:footer>
        <div class="hidden sm:flex sm:items-center sm:ms-6">
            <x-dropdown position="top" align="right" width="48">
                <x-slot name="trigger">
                    <button
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <div>John Paul</div>

                        <div class="ms-2">
                            <i class="ph-bold ph-caret-down"></i>
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">
                        Profile
                    </x-dropdown-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            Log Out
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </x-slot:footer>

    <x-slot:main>
        {{-- Your main content here --}}
        <h1>Main Content Area</h1>
    </x-slot:main>
</x-sidebar.container>
