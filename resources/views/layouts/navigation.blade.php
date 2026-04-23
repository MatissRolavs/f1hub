@auth
    @php
        $favDriver = Auth::user()->favoriteDriver;
        $favConstructor = Auth::user()->favoriteConstructor;
        $favNumber = $favDriver?->permanent_number;
        $favPillColor = $favConstructor
            ? config('f1.team_colors.'.$favConstructor->name, config('f1.default_team_color'))
            : null;
    @endphp
@endauth

<style>
    /* ── Navbar ──────────────────────────────────────────── */
    .f1-nav {
        position: fixed;
        top: 0; left: 0; right: 0;
        z-index: 50;
        background: linear-gradient(180deg, #0a0a0f 0%, #15151e 100%);
        border-bottom: 1px solid rgba(255,255,255,0.08);
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
    }
    .f1-nav::after {
        content: "";
        position: absolute;
        left: 0; bottom: -1px;
        width: 100%; height: 2px;
        background: linear-gradient(90deg, transparent 0%, #e10600 30%, #e10600 70%, transparent 100%);
        box-shadow: 0 0 15px rgba(225,6,0,0.6);
        opacity: 0.6;
    }
    .f1-nav-inner {
        position: relative;
        max-width: 80rem;
        margin: 0 auto;
        padding: 0 1rem;
    }
    @media (min-width: 640px) { .f1-nav-inner { padding: 0 1.5rem; } }
    @media (min-width: 1024px) { .f1-nav-inner { padding: 0 2rem; } }

    /* ── Logo ────────────────────────────────────────────── */
    .nav-logo {
        transition: transform 0.25s ease, filter 0.25s ease;
    }
    .nav-logo:hover {
        transform: scale(1.05);
        filter: drop-shadow(0 0 12px rgba(225,6,0,0.6));
    }

    /* ── Nav link ────────────────────────────────────────── */
    .nav-link {
        position: relative;
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 0.25rem;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 2px;
        color: rgba(255,255,255,0.7);
        transition: color 0.25s ease;
    }
    .nav-link::after {
        content: "";
        position: absolute;
        left: 0; right: 0; bottom: -1px;
        height: 2px;
        background: #e10600;
        transform: scaleX(0);
        transform-origin: center;
        transition: transform 0.3s cubic-bezier(.2,.65,.3,1);
        box-shadow: 0 0 12px rgba(225,6,0,0.8);
    }
    .nav-link:hover { color: white; }
    .nav-link:hover::after,
    .nav-link.active::after { transform: scaleX(1); }
    .nav-link.active { color: white; }

    /* ── Dropdown trigger + menu ─────────────────────────── */
    .nav-dropdown-trigger {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.5rem 0.25rem;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 2px;
        color: rgba(255,255,255,0.7);
        transition: color 0.25s ease;
        background: transparent;
        border: none;
        cursor: pointer;
    }
    .nav-dropdown-trigger::after {
        content: "";
        position: absolute;
        left: 0; right: 0; bottom: -1px;
        height: 2px;
        background: #e10600;
        transform: scaleX(0);
        transform-origin: center;
        transition: transform 0.3s cubic-bezier(.2,.65,.3,1);
        box-shadow: 0 0 12px rgba(225,6,0,0.8);
    }
    .nav-dropdown-wrapper:hover .nav-dropdown-trigger,
    .nav-dropdown-trigger.active { color: white; }
    .nav-dropdown-wrapper:hover .nav-dropdown-trigger::after,
    .nav-dropdown-trigger.active::after { transform: scaleX(1); }

    .nav-dropdown {
        background: linear-gradient(180deg, #15151e 0%, #0a0a0f 100%);
        border: 1px solid rgba(255,255,255,0.08);
        border-top: 2px solid #e10600;
        border-radius: 0 0 0.75rem 0.75rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.6);
        min-width: 13rem;
    }
    .nav-dropdown a {
        display: block;
        padding: 0.65rem 1rem;
        font-size: 0.8rem;
        letter-spacing: 1.5px;
        color: rgba(255,255,255,0.75);
        transition: background 0.2s ease, color 0.2s ease, padding-left 0.25s ease;
        border-left: 3px solid transparent;
    }
    .nav-dropdown a:hover {
        background: rgba(225,6,0,0.1);
        color: white;
        border-left-color: #e10600;
        padding-left: 1.25rem;
    }

    /* ── Right-side actions ──────────────────────────────── */
    .user-chip {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.9rem;
        border-radius: 9999px;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.15);
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 1px;
        color: rgba(255,255,255,0.9);
        transition: border-color 0.25s ease, background 0.25s ease, box-shadow 0.25s ease;
    }
    .user-chip:hover {
        background: rgba(255,255,255,0.08);
        border-color: rgba(255,255,255,0.35);
    }
    .user-chip.active {
        border-color: rgba(225,6,0,0.6);
        box-shadow: 0 0 12px rgba(225,6,0,0.3);
    }

    .logout-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        background: transparent;
        border: 1px solid rgba(225,6,0,0.5);
        color: #fca5a5;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        transition: background 0.25s ease, color 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }
    .logout-btn:hover {
        background: rgba(225,6,0,0.15);
        color: white;
        border-color: #e10600;
        box-shadow: 0 0 15px rgba(225,6,0,0.45);
    }

    .guest-link {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        color: rgba(255,255,255,0.8);
        transition: all 0.25s ease;
    }
    .guest-link:hover { color: white; background: rgba(255,255,255,0.06); }
    .guest-link.primary {
        background: linear-gradient(90deg, #e10600 0%, #a30400 100%);
        color: white;
    }
    .guest-link.primary:hover {
        box-shadow: 0 4px 15px rgba(225,6,0,0.5);
    }

    /* ── Number pill ─────────────────────────────────────── */
    .fav-pill {
        display: inline-flex;
        align-items: center;
        border-radius: 9999px;
        padding: 0.1rem 0.55rem;
        font-size: 0.7rem;
        font-weight: 800;
        color: white;
        text-shadow: 0 1px 1px rgba(0,0,0,0.6);
        box-shadow: 0 0 8px rgba(0,0,0,0.4);
    }

    /* ── Hamburger ───────────────────────────────────────── */
    .hamburger {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem;
        border-radius: 0.5rem;
        color: white;
        transition: background 0.25s ease, color 0.25s ease;
    }
    .hamburger:hover { background: rgba(225,6,0,0.15); color: #fca5a5; }

    /* ── Mobile menu ─────────────────────────────────────── */
    .mobile-menu {
        background: linear-gradient(180deg, #0a0a0f 0%, #15151e 100%);
        border-top: 1px solid rgba(255,255,255,0.08);
    }
    .mobile-link {
        display: block;
        padding: 0.75rem 1.25rem;
        font-size: 0.85rem;
        letter-spacing: 2px;
        font-weight: 600;
        color: rgba(255,255,255,0.75);
        border-left: 3px solid transparent;
        transition: all 0.2s ease;
    }
    .mobile-link:hover,
    .mobile-link.active {
        background: rgba(225,6,0,0.1);
        color: white;
        border-left-color: #e10600;
    }
</style>

<nav x-data="{ open: false }" class="f1-nav audiowide-regular">
    <div class="f1-nav-inner">
        <div class="flex items-center justify-between h-16">

            {{-- Left: Logo + Links --}}
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 shrink-0">
                    <img src="https://copilot.microsoft.com/th/id/BCO.1853a237-4c9c-4232-84b3-e8ae2bb8df46.png"
                         alt="F1 Logo"
                         class="nav-logo w-11 h-11 object-contain">
                </a>

                <div class="hidden sm:flex items-center gap-6 lg:gap-8 sm:ml-10 h-16">
                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        {{ __('HOME') }}
                    </a>
                    <a href="{{ route('drivers.index') }}"
                       class="nav-link {{ request()->routeIs('drivers.*') ? 'active' : '' }}">
                        {{ __('DRIVERS') }}
                    </a>
                    <div class="nav-dropdown-wrapper relative h-16 flex items-center"
                         x-data="{ open: false }"
                         @mouseenter="open = true"
                         @mouseleave="open = false">
                        <button type="button"
                                class="nav-dropdown-trigger {{ request()->routeIs('races.*') || request()->routeIs('live-chat') ? 'active' : '' }}">
                            {{ __('RACES') }}
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                      d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" x-transition
                             class="nav-dropdown absolute top-full left-0 z-50">
                            <a href="{{ route('races.index') }}">Race Calendar</a>
                            <a href="{{ route('live-chat') }}">Watch Live</a>
                        </div>
                    </div>

                    <div class="nav-dropdown-wrapper relative h-16 flex items-center"
                         x-data="{ open: false }"
                         @mouseenter="open = true"
                         @mouseleave="open = false">
                        <button type="button"
                                class="nav-dropdown-trigger {{ request()->routeIs('standings.*') ? 'active' : '' }}">
                            {{ __('STANDINGS') }}
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                      d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" x-transition
                             class="nav-dropdown absolute top-full left-0 z-50">
                            <a href="{{ route('standings.index', ['season' => now()->year]) }}">
                                Driver Standings
                            </a>
                            <a href="{{ route('standings.constructors', ['season' => now()->year]) }}">
                                Constructor Standings
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('forums.index') }}"
                       class="nav-link {{ request()->routeIs('forums.*') ? 'active' : '' }}">
                        {{ __('FORUMS') }}
                    </a>
                    <a href="{{ route('game.index') }}"
                       class="nav-link {{ request()->routeIs('game.*') ? 'active' : '' }}">
                        {{ __('PREDICTION GAME') }}
                    </a>
                    @auth
                        @if (Auth::user()->role == 'admin')
                            <a href="{{ route('admin.panel') }}"
                               class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                                {{ __('ADMIN') }}
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- Right: Auth actions --}}
            <div class="hidden sm:flex sm:items-center gap-3">
                @auth
                    <a href="{{ route('profile.edit') }}"
                       class="user-chip {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <span>{{ Auth::user()->name }}</span>
                        @if($favNumber && $favPillColor)
                            <span class="fav-pill" style="background-color: {{ $favPillColor }};">
                                #{{ $favNumber }}
                            </span>
                        @endif
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="guest-link">{{ __('Login') }}</a>
                    <a href="{{ route('register') }}" class="guest-link primary">{{ __('Register') }}</a>
                @endguest
            </div>

            {{-- Hamburger --}}
            <div class="flex items-center sm:hidden">
                <button @click="open = !open" class="hamburger" aria-label="Toggle navigation">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div :class="{'block': open, 'hidden': !open}" class="mobile-menu hidden sm:hidden">
        <div class="py-2">
            <a href="{{ route('dashboard') }}"
               class="mobile-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">{{ __('HOME') }}</a>
            <a href="{{ route('drivers.index') }}"
               class="mobile-link {{ request()->routeIs('drivers.*') ? 'active' : '' }}">{{ __('DRIVERS') }}</a>
            <a href="{{ route('races.index') }}"
               class="mobile-link {{ request()->routeIs('races.*') ? 'active' : '' }}">{{ __('RACE CALENDAR') }}</a>
            <a href="{{ route('live-chat') }}"
               class="mobile-link {{ request()->routeIs('live-chat') ? 'active' : '' }}">{{ __('WATCH LIVE') }}</a>
            <a href="{{ route('standings.index') }}"
               class="mobile-link {{ request()->routeIs('standings.index') ? 'active' : '' }}">{{ __('DRIVER STANDINGS') }}</a>
            <a href="{{ route('standings.constructors', ['season' => now()->year]) }}"
               class="mobile-link {{ request()->routeIs('standings.constructors') ? 'active' : '' }}">{{ __('CONSTRUCTOR STANDINGS') }}</a>
            <a href="{{ route('forums.index') }}"
               class="mobile-link {{ request()->routeIs('forums.*') ? 'active' : '' }}">{{ __('FORUMS') }}</a>
            <a href="{{ route('game.index') }}"
               class="mobile-link {{ request()->routeIs('game.*') ? 'active' : '' }}">{{ __('PREDICTION GAME') }}</a>
            @auth
                @if (Auth::user()->role == 'admin')
                    <a href="{{ route('admin.panel') }}"
                       class="mobile-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">{{ __('ADMIN') }}</a>
                @endif
            @endauth
        </div>

        @auth
            <div class="border-t border-white/10 py-3 px-5">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 mb-1">
                    <span class="text-white font-semibold">{{ Auth::user()->name }}</span>
                    @if($favNumber && $favPillColor)
                        <span class="fav-pill" style="background-color: {{ $favPillColor }};">
                            #{{ $favNumber }}
                        </span>
                    @endif
                </a>
                <div class="text-xs text-white/50">{{ Auth::user()->email }}</div>

                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="logout-btn w-full justify-center">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        @endauth

        @guest
            <div class="border-t border-white/10 py-3 px-5 flex flex-col gap-2">
                <a href="{{ route('login') }}" class="guest-link w-full justify-center">{{ __('Login') }}</a>
                <a href="{{ route('register') }}" class="guest-link primary w-full justify-center">{{ __('Register') }}</a>
            </div>
        @endguest
    </div>
</nav>
