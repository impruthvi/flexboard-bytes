<nav x-data="{ open: false }" class="bg-void-card/80 backdrop-blur-xl border-b border-glass-border sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <!-- FlexBoard Logo -->
                        <div class="relative">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-neon-cyan via-neon-pink to-neon-purple flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300">
                                <span class="text-xl font-bold text-void">F</span>
                            </div>
                            <div class="absolute inset-0 rounded-xl bg-gradient-to-br from-neon-cyan via-neon-pink to-neon-purple opacity-50 blur-lg group-hover:opacity-75 transition-opacity"></div>
                        </div>
                        <span class="font-display text-xl font-bold gradient-text hidden sm:block">FlexBoard</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:flex sm:items-center sm:ml-10 space-x-1">
                    <a href="{{ route('dashboard') }}"
                       class="nav-link-neon {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                            Dashboard
                        </span>
                    </a>
                    <a href="#"
                       class="nav-link-neon">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            Projects
                        </span>
                    </a>
                    <a href="#"
                       class="nav-link-neon">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            Leaderboard
                        </span>
                    </a>
                </div>
            </div>

            <!-- Right Side -->
            <div class="hidden sm:flex sm:items-center sm:gap-4">
                @auth
                    <!-- Points Display -->
                    <div class="points-badge">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <span>{{ number_format(Auth::user()->points ?? 0) }}</span>
                    </div>

                    <!-- Streak Display -->
                    @if((Auth::user()->current_streak ?? 0) > 0)
                        <div class="streak-fire chip chip-pink">
                            {{ Auth::user()->streak_display }}
                        </div>
                    @endif

                    <!-- User Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-3 p-1 rounded-full hover:bg-glass transition-colors group">
                                <div class="avatar-ring">
                                    <img class="w-8 h-8 object-cover" src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->display_name }}">
                                </div>
                                <span class="text-white/80 font-medium group-hover:text-white transition-colors">
                                    {{ Auth::user()->display_name }}
                                </span>
                                <svg class="w-4 h-4 text-white/40 group-hover:text-white/60 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-3 border-b border-glass-border">
                                <p class="text-sm text-white/60">Signed in as</p>
                                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->email }}</p>
                            </div>

                            <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="flex items-center gap-2 text-neon-pink hover:text-neon-pink">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="btn-neon btn-neon-outline text-sm py-2 px-4">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" class="btn-neon btn-neon-cyan text-sm py-2 px-4">
                        Get Started
                    </a>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-white/60 hover:text-white hover:bg-glass focus:outline-none focus:bg-glass transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-void-card border-t border-glass-border">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="{{ route('dashboard') }}"
               class="block py-3 px-4 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-glass text-neon-cyan' : 'text-white/70 hover:bg-glass hover:text-white' }} transition-colors">
                Dashboard
            </a>
            <a href="#"
               class="block py-3 px-4 rounded-lg text-white/70 hover:bg-glass hover:text-white transition-colors">
                Projects
            </a>
            <a href="#"
               class="block py-3 px-4 rounded-lg text-white/70 hover:bg-glass hover:text-white transition-colors">
                Leaderboard
            </a>
        </div>

        @auth
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-4 border-t border-glass-border">
                <div class="flex items-center gap-4 px-4 mb-4">
                    <div class="avatar-ring">
                        <img class="w-10 h-10 object-cover" src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->display_name }}">
                    </div>
                    <div>
                        <div class="font-medium text-white">{{ Auth::user()->display_name }}</div>
                        <div class="text-sm text-white/60">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="flex items-center gap-2 px-4 mb-4">
                    <div class="points-badge text-sm">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        {{ number_format(Auth::user()->points ?? 0) }} pts
                    </div>
                    @if((Auth::user()->current_streak ?? 0) > 0)
                        <div class="chip chip-pink text-xs">
                            {{ Auth::user()->streak_display }}
                        </div>
                    @endif
                </div>

                <div class="space-y-1 px-4">
                    <a href="{{ route('profile.edit') }}"
                       class="block py-3 px-4 rounded-lg text-white/70 hover:bg-glass hover:text-white transition-colors">
                        Profile
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left py-3 px-4 rounded-lg text-neon-pink hover:bg-glass transition-colors">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>
