<x-app-layout>
<style>
    /* ── Scroll reveal ───────────────────────────────────── */
    .reveal, .reveal-scale {
        opacity: 0;
        transition: opacity 0.9s cubic-bezier(.2,.65,.3,1), transform 0.9s cubic-bezier(.2,.65,.3,1);
        will-change: opacity, transform;
    }
    .reveal       { transform: translateY(40px); }
    .reveal-scale { transform: scale(0.96); }
    .is-visible   { opacity: 1 !important; transform: none !important; }

    .reveal-stagger > * {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.7s cubic-bezier(.2,.65,.3,1), transform 0.7s cubic-bezier(.2,.65,.3,1);
    }
    .reveal-stagger.is-visible > *  { opacity: 1; transform: none; }
    .reveal-stagger.is-visible > *:nth-child(1) { transition-delay: 0s; }
    .reveal-stagger.is-visible > *:nth-child(2) { transition-delay: 0.1s; }
    .reveal-stagger.is-visible > *:nth-child(3) { transition-delay: 0.2s; }
    .reveal-stagger.is-visible > *:nth-child(4) { transition-delay: 0.3s; }

    /* ── Hero ─────────────────────────────────────────────── */
    .admin-hero {
        position: relative;
        overflow: hidden;
        background:
            radial-gradient(ellipse at 50% 0%, rgba(225,6,0,0.3) 0%, transparent 55%),
            linear-gradient(180deg, #0a0a0f 0%, #15151e 100%);
    }
    .admin-hero::before {
        content: "";
        position: absolute; inset: 0;
        background-image:
            repeating-linear-gradient(0deg,
                rgba(255,255,255,0.02) 0 1px,
                transparent 1px 3px
            );
        pointer-events: none;
    }
    .admin-hero::after {
        content: "";
        position: absolute; inset: 0;
        background-image: repeating-linear-gradient(
            45deg,
            rgba(225,6,0,0.02) 0 14px,
            transparent 14px 28px
        );
        pointer-events: none;
    }
    .hero-stripe {
        position: absolute; left: 0;
        height: 2px; width: 100%;
        background: linear-gradient(90deg, transparent 0%, #e10600 30%, #e10600 70%, transparent 100%);
        box-shadow: 0 0 15px rgba(225,6,0,0.8);
        z-index: 3;
    }
    .hero-stripe.top    { top: 0; }
    .hero-stripe.bottom { bottom: 0; }

    /* ── Hero headline ───────────────────────────────────── */
    .admin-hero h2 {
        letter-spacing: 4px;
        text-shadow: 0 0 30px rgba(225,6,0,0.35);
    }
    .admin-hero h2 .accent {
        color: #e10600;
        text-shadow: 0 0 20px rgba(225,6,0,0.8);
    }

    /* ── Action card ─────────────────────────────────────── */
    .admin-card {
        position: relative;
        display: flex;
        flex-direction: column;
        padding: 1.75rem;
        background: linear-gradient(135deg, #1a1a28 0%, #0f0f15 100%);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 1rem;
        overflow: hidden;
        transition: transform 0.35s ease, border-color 0.35s ease, box-shadow 0.35s ease;
    }
    .admin-card::before {
        content: "";
        position: absolute; top: 0; left: 0; right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent 0%, #e10600 50%, transparent 100%);
        opacity: 0.5;
        transition: opacity 0.35s ease;
    }
    .admin-card::after {
        content: "";
        position: absolute;
        top: -1px; bottom: -1px; right: -1px;
        width: 2px;
        background: #e10600;
        box-shadow: 0 0 12px rgba(225,6,0,0.7);
        transform: scaleY(0);
        transform-origin: bottom;
        transition: transform 0.45s cubic-bezier(.2,.65,.3,1);
    }
    .admin-card:hover {
        transform: translateY(-6px);
        border-color: rgba(225,6,0,0.35);
        box-shadow: 0 0 30px rgba(225,6,0,0.35), 0 10px 30px rgba(0,0,0,0.6);
    }
    .admin-card:hover::before { opacity: 1; }
    .admin-card:hover::after  { transform: scaleY(1); }

    .card-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 3rem; height: 3rem;
        border-radius: 0.75rem;
        background: rgba(225,6,0,0.1);
        border: 1px solid rgba(225,6,0,0.4);
        color: #fca5a5;
        transition: background 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
    }
    .admin-card:hover .card-icon {
        background: #e10600;
        color: white;
        box-shadow: 0 0 20px rgba(225,6,0,0.6);
    }
    .card-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.2rem 0.65rem;
        border-radius: 9999px;
        background: rgba(225,6,0,0.08);
        border: 1px solid rgba(225,6,0,0.3);
        font-family: "IBM Plex Mono", monospace, ui-monospace;
        font-size: 0.65rem;
        letter-spacing: 2px;
        color: #fca5a5;
        text-transform: uppercase;
    }

    .card-arrow {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        color: rgba(255,255,255,0.5);
        font-size: 0.7rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        transition: color 0.25s ease, gap 0.25s ease;
    }
    .admin-card:hover .card-arrow {
        color: #fca5a5;
        gap: 0.75rem;
    }

    @media (prefers-reduced-motion: reduce) {
        .reveal, .reveal-scale, .reveal-stagger > * {
            opacity: 1 !important; transform: none !important; transition: none !important;
        }
    }
</style>

{{-- ───────────────────────── HERO ───────────────────────── --}}
<section class="admin-hero text-white">
    <div class="hero-stripe top"></div>
    <div class="hero-stripe bottom"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 py-14">
        <div class="reveal-scale text-center">
            <p class="audiowide-regular text-xs md:text-sm text-white/60 tracking-[6px] mb-2">
                CONTROL CENTER
            </p>
            <h2 class="audiowide-regular text-4xl md:text-5xl lg:text-6xl font-bold mb-4">
                Admin <span class="accent">Panel</span>
            </h2>
            <p class="text-gray-300 max-w-2xl mx-auto text-base md:text-lg">
                Sync race data, manage tracks, and keep the F1 Hub in fighting shape.
            </p>
        </div>
    </div>
</section>

{{-- ───────────────────────── ACTION CARDS ───────────────────────── --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-white audiowide-regular">

    <h3 class="text-xl md:text-2xl font-bold uppercase mb-8 reveal tracking-widest" style="padding-left: 1.25rem; position: relative; display: inline-block;">
        <span style="content: ''; position: absolute; left: 0; top: 8%; bottom: 8%; width: 6px; background: #e10600; box-shadow: 0 0 12px rgba(225,6,0,0.6); display: block;"></span>
        Operations
    </h3>

    <div class="reveal-stagger grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Sync Data --}}
        <a href="{{ route('admin.data') }}" class="admin-card">
            <div class="flex items-start justify-between mb-5">
                <div class="card-icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0011.667 0l3.181-3.183m-3.181-3.182a8.25 8.25 0 00-11.667 0l-3.181 3.182m0 4.991l3.181-3.183m14.856-1.181a8.25 8.25 0 00-11.667 0L4.152 19.644"/>
                    </svg>
                </div>
                <span class="card-tag">DATA OPS</span>
            </div>

            <h2 class="text-xl md:text-2xl font-bold mb-2">Sync Data Info</h2>
            <p class="text-sm text-white/60 mb-6 flex-1">
                Trigger data synchronization for races, race results, driver standings, and constructor standings from the Jolpica F1 API.
            </p>

            <span class="card-arrow">
                Enter Console
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        </a>

        {{-- Manage Users --}}
        <a href="{{ route('admin.users.index') }}" class="admin-card">
            <div class="flex items-start justify-between mb-5">
                <div class="card-icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span class="card-tag">USERS</span>
            </div>
            <h2 class="text-xl md:text-2xl font-bold mb-2">Manage Users</h2>
            <p class="text-sm text-white/60 mb-6 flex-1">
                Search users, change roles between user and admin, reset passwords, and delete accounts from the platform.
            </p>
            <span class="card-arrow">
                Enter Console
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        </a>

        {{-- Manage Races --}}
        <a href="{{ route('admin.races.index') }}" class="admin-card">
            <div class="flex items-start justify-between mb-5">
                <div class="card-icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"/>
                    </svg>
                </div>
                <span class="card-tag">CONTENT</span>
            </div>

            <h2 class="text-xl md:text-2xl font-bold mb-2">Manage Races</h2>
            <p class="text-sm text-white/60 mb-6 flex-1">
                Edit race details, update circuit info, track length, turns, lap records, and descriptions for each Grand Prix in the calendar.
            </p>

            <span class="card-arrow">
                Enter Console
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        </a>
    </div>
</div>

<script>
(function(){
    const targets = document.querySelectorAll('.reveal, .reveal-scale, .reveal-stagger');
    if (!('IntersectionObserver' in window) || !targets.length) {
        targets.forEach(el => el.classList.add('is-visible'));
        return;
    }
    const io = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                io.unobserve(entry.target);
            }
        });
    }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
    targets.forEach(el => io.observe(el));
})();
</script>
</x-app-layout>
