<nav x-data="{ open: false }" class="bg-gray-900 border-b border-gray-100 font-mono text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 bg-gray-900 rounded-2xl">
        <div class="flex items-center justify-between h-16">

            <!-- Left side: Logo + Links -->
            <div class="flex items-center">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <img src="https://copilot.microsoft.com/th/id/BCO.1853a237-4c9c-4232-84b3-e8ae2bb8df46.png" 
                         alt="F1 Logo" 
                         class="w-12 h-12 object-contain hover:scale-105 transition-transform duration-200">
                </a>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white audiowide-regular">
                        {{ __('HOME') }}
                    </x-nav-link>
                    <x-nav-link :href="route('drivers.index')" :active="request()->routeIs('drivers.index')" class="text-white audiowide-regular">
                        {{ __('DRIVERS') }}
                    </x-nav-link>
                    <x-nav-link :href="route('races.index')" :active="request()->routeIs('races.index')" class="text-white audiowide-regular">
                        {{ __('RACES') }}
                    </x-nav-link>
                    <x-nav-link :href="route('standings.index')" :active="request()->routeIs('standings.index')" class="text-white audiowide-regular">
                        {{ __('STANDINGS') }}
                    </x-nav-link>
                    <x-nav-link :href="route('forums.index')" :active="request()->routeIs('forums.index')" class="text-white audiowide-regular">
                        {{ __('FORUMS') }}
                    </x-nav-link>
                    <x-nav-link :href="route('game.index')" :active="request()->routeIs('game.index')" class="text-white audiowide-regular">
                        {{ __('PREDICTION GAME') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.panel')" :active="request()->routeIs('admin.panel')" class="text-white audiowide-regular">
                        {{ __('ADMIN') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Right side: Username + Logout -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">
                <!-- Username links directly to profile -->
                <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')" class="text-white audiowide-regular">
                    {{ Auth::user()->name }}
                </x-nav-link>

                <!-- Logout button -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-red-500 hover:text-red-400 audiowide-regular">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-gray-300 hover:bg-gray-800 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden audiowide-regular text-white">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white audiowide-regular">
                {{ __('HOME') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('drivers.index')" :active="request()->routeIs('drivers.index')" class="text-white audiowide-regular">
                {{ __('DRIVERS') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('races.index')" :active="request()->routeIs('races.index')" class="text-white audiowide-regular">
                {{ __('RACES') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('standings.index')" :active="request()->routeIs('standings.index')" class="text-white audiowide-regular">
                {{ __('STANDINGS') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('forums.index')" :active="request()->routeIs('forums.index')" class="text-white audiowide-regular">
                {{ __('FORUMS') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('game.index')" :active="request()->routeIs('game.index')" class="text-white audiowide-regular">
                {{ __('PREDICTION GAME') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-700">
            <div class="px-4">
                <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')" class="text-white audiowide-regular">
                    {{ Auth::user()->name }}
                </x-nav-link>

                <div class="font-medium text-sm text-gray-300">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-500 font-mono">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
