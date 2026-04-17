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
    .reveal-stagger.is-visible > *:nth-child(1) { transition-delay: 0.00s; }
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
    .race-hero {
        position: relative;
        overflow: hidden;
        background:
            radial-gradient(ellipse at 50% 20%, rgba(225,6,0,0.25) 0%, transparent 55%),
            linear-gradient(135deg, #0a0a0f 0%, #15151e 60%, #0a0a0f 100%);
    }
    .race-hero::before {
        content: "";
        position: absolute; inset: 0;
        background-image: repeating-linear-gradient(
            45deg,
            rgba(255,255,255,0.02) 0 14px,
            transparent 14px 28px
        );
        pointer-events: none;
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

    .race-hero h2 {
        letter-spacing: 3px;
        text-shadow: 0 0 30px rgba(225,6,0,0.35);
    }
    .race-hero h2 .accent {
        color: #e10600;
        text-shadow: 0 0 20px rgba(225,6,0,0.8);
    }

    .round-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.4rem 1rem;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: rgba(255,255,255,0.85);
    }

    /* ── Podium cards ────────────────────────────────────── */
    .podium-card {
        position: relative;
        padding: 1.75rem 1.5rem;
        border-radius: 1rem;
        color: white;
        overflow: hidden;
        transition: transform 0.35s ease, box-shadow 0.35s ease;
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
    }
    .podium-card::before {
        content: "";
        position: absolute; inset: 0;
        background: linear-gradient(135deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.35) 100%);
        pointer-events: none;
    }
    .podium-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 0 30px rgba(225,6,0,0.4), 0 10px 30px rgba(0,0,0,0.6);
    }
    .podium-card .pos-medal {
        position: absolute;
        top: 1rem; right: 1rem;
        width: 3.25rem; height: 3.25rem;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.35rem;
        border: 2px solid rgba(255,255,255,0.35);
        z-index: 2;
    }
    .medal-1 { background: linear-gradient(135deg, #facc15 0%, #b45309 100%); color: #000; }
    .medal-2 { background: linear-gradient(135deg, #e5e7eb 0%, #6b7280 100%); color: #000; }
    .medal-3 { background: linear-gradient(135deg, #fb923c 0%, #9a3412 100%); color: #000; }

    /* ── Classification table ────────────────────────────── */
    .results-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 6px;
    }
    .results-table thead th {
        background: transparent;
        color: rgba(255,255,255,0.5);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 2px;
        padding: 0.75rem 1rem;
        text-align: left;
    }
    .result-row {
        background: rgba(255,255,255,0.03);
        transition: background 0.25s ease;
    }
    .result-row:hover { background: rgba(225,6,0,0.1); }
    .result-row td {
        padding: 0.9rem 1rem;
        color: white;
        border-top: 1px solid rgba(255,255,255,0.06);
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .result-row td:first-child {
        border-left: 1px solid rgba(255,255,255,0.06);
        border-top-left-radius: 0.5rem;
        border-bottom-left-radius: 0.5rem;
    }
    .result-row td:last-child {
        border-right: 1px solid rgba(255,255,255,0.06);
        border-top-right-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
    }

    .pos-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2.25rem; height: 2.25rem;
        padding: 0 0.5rem;
        border-radius: 9999px;
        font-weight: 800;
        font-size: 0.9rem;
        border: 2px solid rgba(255,255,255,0.15);
        background: rgba(0,0,0,0.35);
        color: white;
    }
    .pos-badge.pos-1 { background: linear-gradient(135deg, #facc15 0%, #b45309 100%); color: #000; border-color: rgba(250,204,21,0.6); }
    .pos-badge.pos-2 { background: linear-gradient(135deg, #e5e7eb 0%, #6b7280 100%); color: #000; border-color: rgba(229,231,235,0.6); }
    .pos-badge.pos-3 { background: linear-gradient(135deg, #fb923c 0%, #9a3412 100%); color: #000; border-color: rgba(251,146,60,0.6); }
    .pos-badge.dnf   { background: rgba(60,0,0,0.5); border-color: rgba(225,6,0,0.4); color: #fca5a5; font-size: 0.7rem; letter-spacing: 1px; }

    .team-stripe {
        display: inline-block;
        width: 4px;
        height: 1.75rem;
        border-radius: 2px;
        vertical-align: middle;
        margin-right: 0.75rem;
    }

    .fastest-lap {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-variant-numeric: tabular-nums;
    }
    .fastest-lap.best {
        color: #c084fc;
        font-weight: 700;
    }
    .fastest-lap.best::before {
        content: "⚡";
        color: #a855f7;
    }

    .points-cell {
        font-variant-numeric: tabular-nums;
        font-weight: 700;
        font-size: 1.05rem;
    }

    /* ── Buttons ─────────────────────────────────────────── */
    .btn-ghost {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.25);
        border-radius: 0.5rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: white;
        transition: border-color 0.25s ease, background 0.25s ease;
    }
    .btn-ghost:hover {
        border-color: rgba(255,255,255,0.7);
        background: rgba(255,255,255,0.08);
    }

    @media (prefers-reduced-motion: reduce) {
        .reveal, .reveal-scale, .reveal-stagger > * {
            opacity: 1 !important; transform: none !important; transition: none !important;
        }
    }
</style>

{{-- ───────────────────────── HERO ───────────────────────── --}}
<section class="race-hero text-white">
    <div class="hero-stripe top"></div>
    <div class="hero-stripe bottom"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 py-14 text-center">
        <div class="reveal-scale">
            <div class="flex justify-center gap-3 mb-4 flex-wrap">
                <span class="round-badge">{{ $season }} Season</span>
                <span class="round-badge">Round {{ $round }}</span>
                <span class="round-badge">🏁 Results</span>
            </div>

            <h2 class="audiowide-regular text-3xl md:text-5xl lg:text-6xl font-bold mb-3">
                {{ $raceName }}
            </h2>

            <p class="text-gray-300 text-base md:text-lg">
                {{ \Carbon\Carbon::parse($raceDate)->format('l, d F Y') }}
            </p>
        </div>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-white audiowide-regular">

    {{-- ───────────────────────── PODIUM ───────────────────────── --}}
    @php
        $podium = $results->take(3);
    @endphp
    @if($podium->count())
        <div class="reveal-stagger grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            @foreach($podium as $row)
                @php
                    $teamColor = config('f1.team_colors.' . $row->constructor_name, config('f1.default_team_color'));
                    $pos = (int) $row->position;
                @endphp
                <div class="podium-card" style="background-color: {{ $teamColor }};">
                    <div class="pos-medal medal-{{ $pos ?: 1 }}">P{{ $pos ?: $row->position_text }}</div>
                    <div class="relative z-10">
                        <p class="text-xs uppercase tracking-[3px] opacity-80">{{ $row->constructor_name }}</p>
                        <h3 class="audiowide-regular text-2xl md:text-3xl font-bold leading-tight mt-1 mb-5">
                            {{ $row->given_name }} {{ $row->family_name }}
                            @if($row->code)
                                <span class="block text-sm font-normal opacity-70 mt-1">{{ $row->code }}</span>
                            @endif
                        </h3>
                        <div class="flex items-end justify-between gap-3">
                            <div>
                                <p class="text-xs uppercase tracking-widest opacity-80">Time</p>
                                <p class="audiowide-regular text-lg md:text-xl font-bold leading-none tabular-nums mt-1">
                                    {{ $row->race_time ?? $row->status ?? '—' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs uppercase tracking-widest opacity-80">Points</p>
                                <p class="audiowide-regular text-3xl md:text-4xl font-extrabold leading-none">{{ $row->points }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- ───────────────────────── FULL CLASSIFICATION ───────────────────────── --}}
    <h3 class="section-title text-2xl md:text-3xl font-bold uppercase mb-6 reveal">
        Full Classification
    </h3>

    <div class="overflow-x-auto reveal">
        <table class="results-table">
            <thead>
                <tr>
                    <th class="w-16">Pos</th>
                    <th>Driver</th>
                    <th class="hidden lg:table-cell">Nationality</th>
                    <th>Team</th>
                    <th class="text-center hidden md:table-cell">Grid</th>
                    <th class="text-center hidden md:table-cell">Laps</th>
                    <th class="hidden lg:table-cell">Time / Status</th>
                    <th class="text-right">Pts</th>
                    <th class="hidden xl:table-cell">Fastest Lap</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $row)
                    @php
                        $teamColor = config('f1.team_colors.' . $row->constructor_name, config('f1.default_team_color'));
                        $pos = (int) $row->position;
                        $isDnf = !is_numeric($row->position_text);
                    @endphp
                    <tr class="result-row">
                        <td>
                            @if($isDnf)
                                <span class="pos-badge dnf">{{ $row->position_text }}</span>
                            @else
                                <span class="pos-badge pos-{{ $pos }}">{{ $pos }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="font-bold">{{ $row->given_name }} {{ $row->family_name }}</span>
                            @if($row->code)
                                <span class="text-white/40 text-xs ml-1">{{ $row->code }}</span>
                            @endif
                        </td>
                        <td class="hidden lg:table-cell text-white/70 text-sm">{{ $row->driver_nationality }}</td>
                        <td>
                            <span class="team-stripe" style="background-color: {{ $teamColor }};"></span>
                            <span class="text-white/90">{{ $row->constructor_name }}</span>
                        </td>
                        <td class="text-center hidden md:table-cell tabular-nums text-white/70">{{ $row->grid }}</td>
                        <td class="text-center hidden md:table-cell tabular-nums text-white/70">{{ $row->laps }}</td>
                        <td class="hidden lg:table-cell text-white/70 text-sm tabular-nums">
                            {{ $row->race_time ?? $row->status ?? '—' }}
                        </td>
                        <td class="text-right points-cell">{{ $row->points }}</td>
                        <td class="hidden xl:table-cell text-sm">
                            @if($row->fastest_lap_time)
                                <span class="fastest-lap {{ (string) $row->fastest_lap_rank === '1' ? 'best' : '' }}">
                                    {{ $row->fastest_lap_time }}
                                </span>
                            @else
                                <span class="text-white/30">—</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-10 text-center">
        <a href="{{ route('races.index', ['season' => $season]) }}" class="btn-ghost">
            ← Back to {{ $season }} Schedule
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
