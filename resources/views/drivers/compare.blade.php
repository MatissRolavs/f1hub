<x-app-layout>
@php
    $constructorName = $driver->latestStanding->constructor->name ?? 'Unknown';
    $teamColor       = config('f1.team_colors.' . $constructorName, config('f1.default_team_color'));
    $flagCode        = config('f1.nationality_flags.' . $driver->nationality, config('f1.default_flag_code'));
    $flagUrl         = "https://flagcdn.com/w40/" . $flagCode . ".png";

    // Helper: which season "wins" each stat (returns 'a', 'b', or null for tie/missing)
    $betterPos    = ($statsA['position'] && $statsB['position'])
                        ? ($statsA['position'] < $statsB['position'] ? 'a' : ($statsB['position'] < $statsA['position'] ? 'b' : null))
                        : null;
    $betterPts    = ($statsA['points'] !== null && $statsB['points'] !== null)
                        ? ($statsA['points'] > $statsB['points'] ? 'a' : ($statsB['points'] > $statsA['points'] ? 'b' : null))
                        : null;
    $betterWins   = ($statsA['wins'] !== null && $statsB['wins'] !== null)
                        ? ($statsA['wins'] > $statsB['wins'] ? 'a' : ($statsB['wins'] > $statsA['wins'] ? 'b' : null))
                        : null;
    $betterEntries= ($statsA['entries'] !== null && $statsB['entries'] !== null)
                        ? ($statsA['entries'] > $statsB['entries'] ? 'a' : ($statsB['entries'] > $statsA['entries'] ? 'b' : null))
                        : null;
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
        left: 0; top: 8%; bottom: 8%;
        width: 6px;
        background: #e10600;
        box-shadow: 0 0 12px rgba(225,6,0,0.6);
    }

    /* ── Hero ─────────────────────────────────────────────── */
    .compare-hero {
        position: relative;
        width: 100%;
        height: 22rem;
        overflow: hidden;
    }
    .compare-hero-overlay {
        position: absolute; inset: 0;
        background:
            radial-gradient(ellipse at 70% 50%, rgba(225,6,0,0.15) 0%, transparent 55%),
            linear-gradient(to right, rgba(10,10,15,0.97) 40%, rgba(10,10,15,0.5) 100%),
            linear-gradient(to bottom, rgba(10,10,15,0.3) 0%, rgba(10,10,15,0.85) 100%);
    }
    .compare-hero::before {
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

    /* ── Stat cards ──────────────────────────────────────── */
    .stat-card {
        background: #1a1a1a;
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
        transition: box-shadow 0.35s ease;
    }
    .stat-card:hover {
        box-shadow: 0 0 30px rgba(225,6,0,0.15), 0 8px 24px rgba(0,0,0,0.5);
    }

    .stat-row {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        align-items: center;
        padding: 0.875rem 0;
        border-bottom: 1px solid rgba(255,255,255,0.06);
        gap: 1rem;
    }
    .stat-row:last-child { border-bottom: none; }

    .stat-val-a { text-align: right; }
    .stat-val-b { text-align: left; }
    .stat-label-center { text-align: center; color: rgba(255,255,255,0.4); font-size: 0.75rem; letter-spacing: 0.08em; }
    .stat-val   { font-size: 1.1rem; font-weight: 700; color: rgba(255,255,255,0.55); transition: color 0.2s; }
    .stat-val.winner { color: #fff; }
    .stat-val.winner-a { text-shadow: 0 0 12px rgba(225,6,0,0.6); color: #fff; }
    .stat-val.winner-b { text-shadow: 0 0 12px rgba(225,6,0,0.6); color: #fff; }

    /* ── Season selector ─────────────────────────────────── */
    .season-select {
        background: rgba(255,255,255,0.08);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.25);
        color: white;
        padding: 0.65rem 1.1rem;
        border-radius: 9999px;
        font-weight: 600;
        letter-spacing: 1.5px;
        transition: border-color 0.25s ease, box-shadow 0.25s ease;
        cursor: pointer;
    }
    .season-select:hover,
    .season-select:focus {
        border-color: rgba(225,6,0,0.9);
        box-shadow: 0 0 15px rgba(225,6,0,0.5);
        outline: none;
    }
    .season-select option { background: #15151e; color: white; }

    /* ── Compare button ──────────────────────────────────── */
    .compare-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.65rem 1.75rem;
        background: rgba(225,6,0,0.2);
        border: 1px solid rgba(225,6,0,0.6);
        color: white;
        border-radius: 9999px;
        font-weight: 600;
        letter-spacing: 1px;
        transition: background 0.25s ease, box-shadow 0.25s ease;
        cursor: pointer;
    }
    .compare-btn:hover {
        background: rgba(225,6,0,0.35);
        box-shadow: 0 0 18px rgba(225,6,0,0.4);
    }

    /* ── Season header badge ─────────────────────────────── */
    .season-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.35rem 1.25rem;
        border-radius: 9999px;
        font-size: 0.85rem;
        font-weight: 700;
        letter-spacing: 2px;
    }
    .season-badge-a {
        background: rgba(225,6,0,0.2);
        border: 1px solid rgba(225,6,0,0.5);
        color: #ff4444;
    }
    .season-badge-b {
        background: rgba(255,255,255,0.07);
        border: 1px solid rgba(255,255,255,0.2);
        color: rgba(255,255,255,0.7);
    }

    @media (prefers-reduced-motion: reduce) {
        .reveal, .reveal-scale { opacity: 1 !important; transform: none !important; transition: none !important; }
    }
</style>

{{-- ───────────────────────── HERO ───────────────────────── --}}
<div class="compare-hero">
    <img
        src="https://media.formula1.com/image/upload/f_webp,c_limit,q_50,w_640/content/dam/fom-website/drivers/2025Drivers/{{ $driver->family_name }}"
        alt="{{ $driver->given_name }} {{ $driver->family_name }}"
        class="absolute inset-0 w-full h-full object-cover"
        style="object-position: center 15%;"
        onerror="this.onerror=null;this.style.display='none';"
    >

    <div class="absolute top-0 left-0 right-0 h-1 z-10" style="background: {{ $teamColor }}; box-shadow: 0 0 20px {{ $teamColor }}88;"></div>
    <div class="compare-hero-overlay"></div>
    <div class="hero-stripe top" style="top:4px;"></div>
    <div class="hero-stripe bottom"></div>

    <div class="relative z-20 h-full flex flex-col justify-center px-6 md:px-12 max-w-7xl mx-auto reveal-scale">
        <div class="flex items-center gap-3 mb-3">
            <img src="{{ $flagUrl }}" alt="{{ $driver->nationality }}" class="w-8 h-5 rounded shadow">
            <span class="audiowide-regular text-xs text-white/50 tracking-widest uppercase">Season Comparison</span>
        </div>
        <h1 class="audiowide-regular text-3xl md:text-5xl font-bold text-white leading-tight">
            {{ $driver->given_name }}
            <span style="color: {{ $teamColor }};">{{ $driver->family_name }}</span>
        </h1>
        <p class="audiowide-regular text-sm text-white/40 tracking-widest mt-2 uppercase">{{ $constructorName }}</p>
    </div>
</div>

{{-- ───────────────────────── CONTENT ───────────────────────── --}}
<div class="bg-gray-900 pb-20">
    <div class="max-w-5xl mx-auto px-4">

        {{-- Season Selector Form --}}
        <div class="relative -mt-8 z-20 mb-12 reveal">
            <div class="stat-card p-6">
                <h3 class="section-title audiowide-regular text-base font-bold uppercase text-white mb-5">
                    Select Seasons
                </h3>
                <form method="GET" action="{{ route('drivers.compare', $driver) }}"
                      class="flex flex-col sm:flex-row gap-4 items-start sm:items-end">

                    <div class="flex flex-col gap-1">
                        <label class="audiowide-regular text-xs text-white/50 tracking-widest uppercase">Season A (Current)</label>
                        <input type="text" name="season_a" value="{{ $seasonA }}" readonly
                               class="audiowide-regular bg-transparent border border-white/20 text-white/60 px-4 py-2 rounded-full w-36 text-center cursor-not-allowed text-sm">
                    </div>

                    <div class="audiowide-regular text-white/30 text-xl self-end pb-2 hidden sm:block">vs</div>

                    <div class="flex flex-col gap-1">
                        <label class="audiowide-regular text-xs text-white/50 tracking-widest uppercase">Season B</label>
                        <select name="season_b" class="season-select audiowide-regular text-sm">
                            @foreach($seasonsB as $season)
                                <option value="{{ $season }}" @if($season == $seasonB) selected @endif>{{ $season }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="compare-btn audiowide-regular text-sm">
                        Compare
                    </button>
                </form>
            </div>
        </div>

        {{-- Comparison table --}}
        <div class="reveal">
            <h3 class="section-title audiowide-regular text-xl font-bold uppercase text-white mb-6">
                Head to Head
            </h3>

            <div class="stat-card overflow-hidden">
                {{-- Season header row --}}
                <div class="grid grid-cols-3 items-center px-6 py-4 border-b border-white/10" style="background: rgba(255,255,255,0.03);">
                    <div class="text-right">
                        <span class="season-badge season-badge-a audiowide-regular">{{ $seasonA }}</span>
                    </div>
                    <div class="text-center">
                        <span class="audiowide-regular text-xs text-white/30 tracking-widest uppercase">Season</span>
                    </div>
                    <div class="text-left">
                        <span class="season-badge season-badge-b audiowide-regular">{{ $seasonB }}</span>
                    </div>
                </div>

                <div class="px-6">
                    {{-- Position --}}
                    <div class="stat-row">
                        <div class="stat-val stat-val-a audiowide-regular {{ $betterPos === 'a' ? 'winner winner-a' : '' }}">
                            {{ $statsA['position'] ?? '—' }}
                        </div>
                        <div class="stat-label-center audiowide-regular">Championship Position</div>
                        <div class="stat-val stat-val-b audiowide-regular {{ $betterPos === 'b' ? 'winner winner-b' : '' }}">
                            {{ $statsB['position'] ?? '—' }}
                        </div>
                    </div>

                    {{-- Points --}}
                    <div class="stat-row">
                        <div class="stat-val stat-val-a audiowide-regular {{ $betterPts === 'a' ? 'winner winner-a' : '' }}">
                            {{ $statsA['points'] ?? '—' }}
                        </div>
                        <div class="stat-label-center audiowide-regular">Points</div>
                        <div class="stat-val stat-val-b audiowide-regular {{ $betterPts === 'b' ? 'winner winner-b' : '' }}">
                            {{ $statsB['points'] ?? '—' }}
                        </div>
                    </div>

                    {{-- Race Wins --}}
                    <div class="stat-row">
                        <div class="stat-val stat-val-a audiowide-regular {{ $betterWins === 'a' ? 'winner winner-a' : '' }}">
                            {{ $statsA['wins'] ?? '—' }}
                        </div>
                        <div class="stat-label-center audiowide-regular">Race Wins</div>
                        <div class="stat-val stat-val-b audiowide-regular {{ $betterWins === 'b' ? 'winner winner-b' : '' }}">
                            {{ $statsB['wins'] ?? '—' }}
                        </div>
                    </div>

                    {{-- Grand Prix Entries --}}
                    <div class="stat-row">
                        <div class="stat-val stat-val-a audiowide-regular {{ $betterEntries === 'a' ? 'winner winner-a' : '' }}">
                            {{ $statsA['entries'] ?? '—' }}
                        </div>
                        <div class="stat-label-center audiowide-regular">Grand Prix Entries</div>
                        <div class="stat-val stat-val-b audiowide-regular {{ $betterEntries === 'b' ? 'winner winner-b' : '' }}">
                            {{ $statsB['entries'] ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Back link --}}
        <div class="mt-8 reveal">
            <a href="{{ route('drivers.show', $driver) }}"
               class="audiowide-regular text-sm text-white/40 hover:text-white/70 transition-colors flex items-center gap-2">
                ← Back to {{ $driver->given_name }} {{ $driver->family_name }}
            </a>
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
