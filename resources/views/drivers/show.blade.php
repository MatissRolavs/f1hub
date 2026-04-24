<x-app-layout>
@php
    $constructorName = $driver->latestStanding->constructor->name ?? 'Unknown';
    $teamColor       = config('f1.team_colors.' . $constructorName, config('f1.default_team_color'));
    $flagCode        = config('f1.nationality_flags.' . $driver->nationality, config('f1.default_flag_code'));
    $flagUrl         = "https://flagcdn.com/w40/" . $flagCode . ".png";
    $position        = $seasonStats['position'] ?? null;
    $rankClass       = match(true) {
        $position === 1 => 'bg-gradient-to-br from-yellow-300 to-yellow-600 text-black',
        $position === 2 => 'bg-gradient-to-br from-gray-200 to-gray-500 text-black',
        $position === 3 => 'bg-gradient-to-br from-orange-400 to-orange-700 text-black',
        default         => 'bg-black/70 text-white',
    };
@endphp

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

    /* ── Section title with red accent ───────────────────── */
    .section-title {
        position: relative;
        padding-left: 1.25rem;
        display: inline-block;
    }
    .section-title::before {
        content: "";
        position: absolute;
        left: 0;
        top: 8%;
        bottom: 8%;
        width: 6px;
        background: #e10600;
        box-shadow: 0 0 12px rgba(225,6,0,0.6);
    }

    /* ── Hero ─────────────────────────────────────────────── */
    .driver-hero {
        position: relative;
        width: 100%;
        height: 38rem;
        overflow: hidden;
    }
    .driver-hero-overlay {
        position: absolute; inset: 0;
        background:
            radial-gradient(ellipse at 70% 50%, rgba(225,6,0,0.2) 0%, transparent 55%),
            linear-gradient(to right, rgba(10,10,15,0.95) 35%, rgba(10,10,15,0.4) 100%),
            linear-gradient(to bottom, rgba(10,10,15,0.3) 0%, rgba(10,10,15,0.8) 100%);
    }
    .driver-hero::before {
        content: "";
        position: absolute; inset: 0;
        background-image: repeating-linear-gradient(
            45deg,
            rgba(255,255,255,0.02) 0 14px,
            transparent 14px 28px
        );
        pointer-events: none;
        z-index: 1;
    }
    .hero-stripe {
        position: absolute; left: 0;
        height: 4px; width: 100%;
        background: linear-gradient(90deg, #e10600 0%, #e10600 55%, transparent 100%);
        box-shadow: 0 0 20px rgba(225,6,0,0.8);
        z-index: 3;
    }
    .hero-stripe.top    { top: 0; }
    .hero-stripe.bottom { bottom: 0; background: linear-gradient(270deg, #e10600 0%, #e10600 55%, transparent 100%); }

    /* ── Stat card ───────────────────────────────────────── */
    .stat-card {
        background: #1a1a1a;
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
        transition: box-shadow 0.35s ease;
    }
    .stat-card:hover {
        box-shadow: 0 0 30px rgba(225,6,0,0.2), 0 8px 24px rgba(0,0,0,0.5);
    }

    .stat-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.625rem 0;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .stat-row:last-child { border-bottom: none; }
    .stat-label { color: rgba(255,255,255,0.5); font-size: 0.85rem; letter-spacing: 0.05em; }
    .stat-value { color: white; font-weight: 700; font-size: 1rem; }

    /* ── Number watermark ────────────────────────────────── */
    .driver-number-watermark {
        font-size: clamp(6rem, 20vw, 14rem);
        font-weight: 900;
        line-height: 1;
        opacity: 0.07;
        letter-spacing: -0.04em;
        user-select: none;
        pointer-events: none;
    }

    /* ── Compare button ──────────────────────────────────── */
    .compare-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 2rem;
        background: rgba(225,6,0,0.15);
        border: 1px solid rgba(225,6,0,0.5);
        color: white;
        border-radius: 9999px;
        font-weight: 600;
        letter-spacing: 1px;
        transition: background 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }
    .compare-btn:hover {
        background: rgba(225,6,0,0.3);
        border-color: #e10600;
        box-shadow: 0 0 20px rgba(225,6,0,0.4);
    }

    @media (prefers-reduced-motion: reduce) {
        .reveal, .reveal-scale { opacity: 1 !important; transform: none !important; transition: none !important; }
    }
</style>

{{-- ───────────────────────── HERO ───────────────────────── --}}
<div class="driver-hero">
    {{-- Driver image as background --}}
    <img
        src="https://media.formula1.com/image/upload/f_webp,c_limit,q_50,w_640/content/dam/fom-website/drivers/2025Drivers/{{ $driver->family_name }}"
        alt="{{ $driver->given_name }} {{ $driver->family_name }}"
        class="absolute inset-0 w-full h-full object-cover object-top"
        style="object-position: center 15%;"
        onerror="this.onerror=null;this.style.display='none';"
    >

    {{-- Team color stripe at top --}}
    <div class="absolute top-0 left-0 right-0 h-1 z-10" style="background: {{ $teamColor }}; box-shadow: 0 0 20px {{ $teamColor }}88;"></div>

    <div class="driver-hero-overlay"></div>
    <div class="hero-stripe top" style="top:4px;"></div>
    <div class="hero-stripe bottom"></div>

    {{-- Number watermark --}}
    <div class="absolute inset-0 flex items-center justify-end pr-8 z-10 overflow-hidden">
        <span class="driver-number-watermark audiowide-regular text-white">
            {{ $driver->permanent_number ?? '' }}
        </span>
    </div>

    {{-- Driver info --}}
    <div class="relative z-20 h-full flex flex-col justify-center px-6 md:px-12 max-w-7xl mx-auto reveal-scale">
        <div class="max-w-lg">
            <div class="flex items-center gap-3 mb-3">
                <img src="{{ $flagUrl }}" alt="{{ $driver->nationality }}" class="w-8 h-5 rounded shadow">
                <span class="audiowide-regular text-sm text-white/60 tracking-widest uppercase">{{ $driver->nationality }}</span>
                @if($position)
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full text-sm font-extrabold audiowide-regular border-2 border-white/30 shadow-lg {{ $rankClass }}">
                        P{{ $position }}
                    </span>
                @endif
            </div>

            <h1 class="audiowide-regular text-4xl md:text-6xl font-bold text-white leading-tight mb-2">
                {{ $driver->given_name }}
            </h1>
            <h1 class="audiowide-regular text-4xl md:text-6xl font-bold leading-tight mb-4" style="color: {{ $teamColor }}; text-shadow: 0 0 30px {{ $teamColor }}88;">
                {{ $driver->family_name }}
            </h1>

            <div class="flex items-center gap-3 mt-2">
                <span class="audiowide-regular text-sm text-white/50 tracking-widest uppercase">Team</span>
                <span class="audiowide-regular text-sm font-bold" style="color: {{ $teamColor }};">{{ $constructorName }}</span>
            </div>
        </div>
    </div>
</div>

{{-- ───────────────────────── CONTENT ───────────────────────── --}}
<div class="bg-gray-900 pb-20">
    <div class="max-w-7xl mx-auto px-4">

        {{-- Stats --}}
        <div class="relative -mt-10 z-20 grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Current Season --}}
            <div class="stat-card p-6 reveal">
                <h3 class="section-title audiowide-regular text-xl font-bold uppercase text-white mb-6">
                    {{ $seasonStats['season'] }} Season
                </h3>
                <div>
                    <div class="stat-row">
                        <span class="stat-label audiowide-regular">Championship Position</span>
                        <span class="stat-value audiowide-regular text-lg">{{ $seasonStats['position'] ?? '—' }}</span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label audiowide-regular">Points</span>
                        <span class="stat-value audiowide-regular text-lg">{{ $seasonStats['points'] ?? '—' }}</span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label audiowide-regular">Grand Prix Entries</span>
                        <span class="stat-value audiowide-regular">{{ $seasonStats['entries'] ?? '—' }}</span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label audiowide-regular">Race Wins</span>
                        <span class="stat-value audiowide-regular">{{ $seasonStats['wins'] ?? '—' }}</span>
                    </div>
                </div>
            </div>

            {{-- Career Stats --}}
            <div class="stat-card p-6 reveal">
                <h3 class="section-title audiowide-regular text-xl font-bold uppercase text-white mb-6">
                    Career Stats
                </h3>
                <div>
                    <div class="stat-row">
                        <span class="stat-label audiowide-regular">Grand Prix Entered</span>
                        <span class="stat-value audiowide-regular">{{ number_format($careerStats['races']) }}</span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label audiowide-regular">Career Points</span>
                        <span class="stat-value audiowide-regular">{{ rtrim(rtrim(number_format($careerStats['points'], 1), '0'), '.') }}</span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label audiowide-regular">Race Wins</span>
                        <span class="stat-value audiowide-regular">{{ number_format($careerStats['wins']) }}</span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label audiowide-regular">Podiums</span>
                        <span class="stat-value audiowide-regular">{{ number_format($careerStats['podiums']) }}</span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label audiowide-regular">Pole Positions</span>
                        <span class="stat-value audiowide-regular">{{ number_format($careerStats['poles']) }}</span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label audiowide-regular">Fastest Laps</span>
                        <span class="stat-value audiowide-regular">{{ number_format($careerStats['fastest_laps']) }}</span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label audiowide-regular">DNFs</span>
                        <span class="stat-value audiowide-regular">{{ number_format($careerStats['dnfs']) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Compare Section --}}
        <div class="mt-12 reveal">
            <h3 class="section-title audiowide-regular text-xl font-bold uppercase text-white mb-6">
                Compare
            </h3>
            <div class="stat-card p-6 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-white/60 audiowide-regular text-sm">Compare this driver's performance across seasons</p>
                <a href="{{ route('drivers.compare', ['driver' => $driver->id, 'type' => 'season', 'season_a' => date('Y'), 'season_b' => date('Y') - 1]) }}"
                   class="compare-btn audiowide-regular text-sm">
                    Compare with {{ date('Y') - 1 }} Season
                </a>
            </div>
        </div>

    </div>
</div>

<script>
(function(){
    const targets = document.querySelectorAll('.reveal, .reveal-scale');
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
    }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' });
    targets.forEach(el => io.observe(el));
})();
</script>
</x-app-layout>
