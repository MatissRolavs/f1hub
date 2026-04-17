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

    /* ── Hero ─────────────────────────────────────────────── */
    .results-hero {
        position: relative;
        overflow: hidden;
        background:
            radial-gradient(ellipse at 50% 20%, rgba(225,6,0,0.25) 0%, transparent 55%),
            linear-gradient(135deg, #0a0a0f 0%, #15151e 60%, #0a0a0f 100%);
    }
    .results-hero::before {
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

    .results-hero h2 {
        letter-spacing: 4px;
        text-shadow: 0 0 30px rgba(225,6,0,0.35);
    }
    .results-hero h2 .accent {
        color: #e10600;
        text-shadow: 0 0 20px rgba(225,6,0,0.8);
    }

    /* ── Summary stat card ───────────────────────────────── */
    .stat-card {
        position: relative;
        padding: 1.5rem;
        border-radius: 1rem;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.1);
        background: linear-gradient(135deg, #1a1a28 0%, #0f0f15 100%);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 0 25px rgba(225,6,0,0.35);
    }
    .stat-card::before {
        content: "";
        position: absolute; top: 0; left: 0; bottom: 0;
        width: 4px;
        background: #e10600;
    }
    .stat-card .num {
        font-family: "Audiowide", sans-serif;
        font-size: 2.5rem;
        font-weight: 800;
        line-height: 1;
        color: #fff;
        text-shadow: 0 0 20px rgba(225,6,0,0.4);
    }
    .stat-card .label {
        font-size: 0.75rem;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: rgba(255,255,255,0.5);
        margin-top: 0.5rem;
    }

    /* ── Score card ──────────────────────────────────────── */
    .score-card {
        position: relative;
        background: linear-gradient(135deg, #1a1a28 0%, #0f0f15 100%);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 1rem;
        padding: 1.5rem;
        overflow: hidden;
        transition: transform 0.35s ease, box-shadow 0.35s ease;
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
    }
    .score-card::before {
        content: "";
        position: absolute; top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, #e10600 0%, #e10600 55%, transparent 100%);
        box-shadow: 0 0 15px rgba(225,6,0,0.6);
    }
    .score-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 0 30px rgba(225,6,0,0.35), 0 10px 30px rgba(0,0,0,0.6);
    }

    .score-big {
        font-family: "Audiowide", sans-serif;
        font-size: 3rem;
        font-weight: 800;
        line-height: 1;
        color: #fff;
        text-shadow: 0 0 25px rgba(225,6,0,0.5);
    }

    /* ── Accuracy bar ────────────────────────────────────── */
    .acc-track {
        width: 100%;
        height: 6px;
        background: rgba(255,255,255,0.08);
        border-radius: 9999px;
        overflow: hidden;
    }
    .acc-fill {
        height: 100%;
        background: linear-gradient(90deg, #e10600 0%, #ff3e34 100%);
        box-shadow: 0 0 10px rgba(225,6,0,0.6);
        border-radius: 9999px;
        transition: width 0.8s ease;
    }

    /* ── Buttons ─────────────────────────────────────────── */
    .btn-f1 {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.85rem 1.75rem;
        background: linear-gradient(90deg, #e10600 0%, #a30400 100%);
        border-radius: 0.5rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: white;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .btn-f1:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(225,6,0,0.55);
    }
    .btn-ghost {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.85rem 1.75rem;
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
        .reveal, .reveal-scale { opacity: 1 !important; transform: none !important; transition: none !important; }
        .acc-fill { transition: none; }
    }
</style>

{{-- ───────────────────────── HERO ───────────────────────── --}}
<section class="results-hero text-white">
    <div class="hero-stripe top"></div>
    <div class="hero-stripe bottom"></div>

    <div class="relative z-10 max-w-5xl mx-auto px-4 py-12 text-center">
        <div class="reveal-scale">
            <p class="audiowide-regular text-xs md:text-sm text-white/60 tracking-[6px] mb-2">
                YOUR SCORECARD
            </p>
            <h2 class="audiowide-regular text-4xl md:text-5xl lg:text-6xl font-bold mb-3">
                Prediction <span class="accent">Results</span>
            </h2>
            <p class="text-gray-300 text-base md:text-lg">
                How your podium guesses stacked up after each race.
            </p>
        </div>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-white audiowide-regular">

    @php
        $totalScore    = $scores->sum('score');
        $totalPossible = $scores->sum('total');
        $accuracy      = $totalPossible > 0 ? round($totalScore / $totalPossible * 100) : 0;
        $racesCount    = $scores->count();
        $bestScore     = $scores->max(fn($s) => $s->total > 0 ? round($s->score / $s->total * 100) : 0) ?? 0;
    @endphp

    @if($scores->isEmpty())
        <div class="bg-white/5 border border-white/10 rounded-xl p-16 text-center max-w-2xl mx-auto">
            <p class="text-gray-400 mb-6 text-lg">No results yet — make a prediction and wait for the race to finish.</p>
            <a href="{{ route('game.index') }}" class="btn-f1">Go to Game</a>
        </div>
    @else
        {{-- Summary stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10 reveal">
            <div class="stat-card">
                <p class="num">{{ $totalScore }}</p>
                <p class="label">Total Points</p>
            </div>
            <div class="stat-card">
                <p class="num">{{ $racesCount }}</p>
                <p class="label">Races Scored</p>
            </div>
            <div class="stat-card">
                <p class="num">{{ $accuracy }}<span class="text-lg">%</span></p>
                <p class="label">Overall Accuracy</p>
            </div>
            <div class="stat-card">
                <p class="num">{{ $bestScore }}<span class="text-lg">%</span></p>
                <p class="label">Best Race</p>
            </div>
        </div>

        {{-- Score cards --}}
        <div class="flex flex-wrap justify-center gap-6">
            @foreach($scores as $score)
                @php
                    $pct = $score->total > 0 ? round($score->score / $score->total * 100) : 0;
                @endphp
                <div class="score-card reveal flex flex-col w-full sm:w-[22rem] lg:w-[24rem]">
                    <p class="text-[0.65rem] text-white/50 tracking-[3px] uppercase mb-1">
                        {{ \Carbon\Carbon::parse($score->created_at)->format('d M Y') }}
                    </p>
                    <h3 class="text-xl md:text-2xl font-bold text-white leading-tight mb-5">
                        {{ strtoupper($score->race_name) }}
                    </h3>

                    <div class="flex items-end justify-between mb-4">
                        <div>
                            <p class="text-xs text-white/50 uppercase tracking-widest">Score</p>
                            <p class="score-big">
                                {{ $score->score }}<span class="text-white/40 text-2xl">/{{ $score->total }}</span>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-white/50 uppercase tracking-widest">Accuracy</p>
                            <p class="score-big">{{ $pct }}<span class="text-2xl">%</span></p>
                        </div>
                    </div>

                    <div class="acc-track mt-auto">
                        <div class="acc-fill" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12 text-center flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('game.myPredictions') }}" class="btn-ghost">My Predictions</a>
            <a href="{{ route('game.index') }}" class="btn-ghost">← Back to Game</a>
        </div>
    @endif
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
    }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
    targets.forEach(el => io.observe(el));
})();
</script>
</x-app-layout>
