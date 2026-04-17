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
    .reveal-stagger.is-visible > * { opacity: 1; transform: none; }
    .reveal-stagger.is-visible > *:nth-child(1) { transition-delay: 0s; }
    .reveal-stagger.is-visible > *:nth-child(2) { transition-delay: 0.08s; }
    .reveal-stagger.is-visible > *:nth-child(3) { transition-delay: 0.16s; }

    /* ── Section title ───────────────────────────────────── */
    .section-title {
        position: relative;
        padding-left: 1.25rem;
        display: inline-block;
    }
    .section-title::before {
        content: "";
        position: absolute;
        left: 0; top: 8%; bottom: 8%;
        width: 6px;
        background: #e10600;
        box-shadow: 0 0 12px rgba(225,6,0,0.6);
    }

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
        background-image: repeating-linear-gradient(0deg,
            rgba(255,255,255,0.02) 0 1px,
            transparent 1px 3px
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

    .admin-hero h2 {
        letter-spacing: 4px;
        text-shadow: 0 0 30px rgba(225,6,0,0.35);
    }
    .admin-hero h2 .accent {
        color: #e10600;
        text-shadow: 0 0 20px rgba(225,6,0,0.8);
    }

    /* ── Sync action card ────────────────────────────────── */
    .sync-card {
        position: relative;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        padding: 1.5rem 1.75rem;
        background: linear-gradient(135deg, #1a1a28 0%, #0f0f15 100%);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 1rem;
        overflow: hidden;
        transition: transform 0.35s ease, border-color 0.35s ease, box-shadow 0.35s ease;
    }
    .sync-card::before {
        content: "";
        position: absolute; top: 0; left: 0; bottom: 0;
        width: 3px;
        background: var(--accent, #e10600);
        box-shadow: 0 0 10px var(--accent, rgba(225,6,0,0.6));
    }
    .sync-card::after {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent 0%, var(--accent, #e10600) 50%, transparent 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .sync-card:hover {
        transform: translateX(4px);
        border-color: var(--accent-border, rgba(225,6,0,0.45));
        box-shadow: 0 0 25px var(--accent-glow, rgba(225,6,0,0.35));
    }
    .sync-card:hover::after { opacity: 0.8; }

    .sync-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 3rem; height: 3rem;
        border-radius: 0.75rem;
        background: rgba(var(--accent-rgb, 225,6,0), 0.12);
        border: 1px solid rgba(var(--accent-rgb, 225,6,0), 0.45);
        color: var(--accent, #fca5a5);
        flex-shrink: 0;
        transition: background 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
    }
    .sync-card:hover .sync-icon {
        background: var(--accent, #e10600);
        color: white;
        box-shadow: 0 0 18px rgba(var(--accent-rgb, 225,6,0), 0.65);
    }

    .sync-card h3 {
        font-size: 1.125rem;
        font-weight: 800;
        color: white;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }
    .sync-card p {
        font-size: 0.8rem;
        color: rgba(255,255,255,0.55);
        margin-top: 0.25rem;
    }
    .sync-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.2rem 0.65rem;
        border-radius: 9999px;
        background: rgba(var(--accent-rgb, 225,6,0), 0.1);
        border: 1px solid rgba(var(--accent-rgb, 225,6,0), 0.35);
        font-family: "IBM Plex Mono", monospace, ui-monospace;
        font-size: 0.6rem;
        letter-spacing: 2px;
        color: var(--accent, #fca5a5);
        text-transform: uppercase;
    }
    .sync-arrow {
        color: rgba(255,255,255,0.4);
        transition: color 0.25s ease, transform 0.25s ease;
        flex-shrink: 0;
    }
    .sync-card:hover .sync-arrow {
        color: var(--accent, #fca5a5);
        transform: translateX(4px);
    }

    /* ── Back link ───────────────────────────────────────── */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1.25rem;
        border-radius: 9999px;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.2);
        color: rgba(255,255,255,0.75);
        font-size: 0.75rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        transition: all 0.25s ease;
    }
    .back-link:hover { border-color: rgba(255,255,255,0.5); color: white; background: rgba(255,255,255,0.08); }

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

    <div class="relative z-10 max-w-5xl mx-auto px-4 py-14">
        <div class="reveal-scale text-center">
            <div class="mb-4">
                <a href="{{ route('admin.panel') }}" class="back-link">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Control Center
                </a>
            </div>

            <p class="audiowide-regular text-xs md:text-sm text-white/60 tracking-[6px] mb-2">
                DATA OPS CONSOLE
            </p>
            <h2 class="audiowide-regular text-4xl md:text-5xl lg:text-6xl font-bold mb-4">
                Sync <span class="accent">Panel</span>
            </h2>
            <p class="text-gray-300 max-w-2xl mx-auto text-base md:text-lg">
                Trigger one-click data sync from the Jolpica F1 API. Past seasons are skipped automatically.
            </p>
        </div>
    </div>
</section>

{{-- ───────────────────────── ACTIONS ───────────────────────── --}}
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-white audiowide-regular">

    @if(session('success'))
        <div class="bg-green-600/20 border border-green-500/40 text-green-200 p-4 rounded-lg mb-8 flex items-center gap-3 reveal">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-900/30 border border-red-500/50 text-red-300 p-4 rounded-lg mb-8 flex items-center gap-3 reveal">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <h3 class="section-title text-lg md:text-xl font-bold uppercase mb-6 reveal">
        Available Operations
    </h3>

    <div class="reveal-stagger space-y-4">
        {{-- Race Results --}}
        <a href="{{ route('results.index') }}"
           class="sync-card"
           style="--accent: #3b82f6; --accent-rgb: 59,130,246; --accent-border: rgba(59,130,246,0.5); --accent-glow: rgba(59,130,246,0.35);">
            <div class="sync-icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3 flex-wrap">
                    <h3>Sync Season Race Results</h3>
                    <span class="sync-tag">ALL SEASONS</span>
                </div>
                <p>Pulls every historical race result. Skips already-synced past seasons; always refreshes the current season.</p>
            </div>
            <svg class="sync-arrow w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        {{-- Season Races --}}
        <a href="{{ route('races.sync') }}"
           class="sync-card"
           style="--accent: #22c55e; --accent-rgb: 34,197,94; --accent-border: rgba(34,197,94,0.5); --accent-glow: rgba(34,197,94,0.35);">
            <div class="sync-icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3 flex-wrap">
                    <h3>Sync Current Season Races</h3>
                    <span class="sync-tag">CALENDAR</span>
                </div>
                <p>Refreshes the race calendar from Jolpica. Skips seasons already on file, prunes orphaned rounds.</p>
            </div>
            <svg class="sync-arrow w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        {{-- Driver Standings --}}
        <a href="{{ route('drivers.sync') }}"
           class="sync-card"
           style="--accent: #facc15; --accent-rgb: 250,204,21; --accent-border: rgba(250,204,21,0.5); --accent-glow: rgba(250,204,21,0.35);">
            <div class="sync-icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3 flex-wrap">
                    <h3>Sync Driver Standings</h3>
                    <span class="sync-tag">STANDINGS</span>
                </div>
                <p>Updates the live driver championship table with the latest points and wins.</p>
            </div>
            <svg class="sync-arrow w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
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
