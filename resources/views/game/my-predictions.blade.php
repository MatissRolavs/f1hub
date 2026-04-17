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
    .preds-hero {
        position: relative;
        overflow: hidden;
        background:
            radial-gradient(ellipse at 50% 20%, rgba(225,6,0,0.25) 0%, transparent 55%),
            linear-gradient(135deg, #0a0a0f 0%, #15151e 60%, #0a0a0f 100%);
    }
    .preds-hero::before {
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

    .preds-hero h2 {
        letter-spacing: 4px;
        text-shadow: 0 0 30px rgba(225,6,0,0.35);
    }
    .preds-hero h2 .accent {
        color: #e10600;
        text-shadow: 0 0 20px rgba(225,6,0,0.8);
    }

    /* ── Prediction card ─────────────────────────────────── */
    .pred-card {
        position: relative;
        background: linear-gradient(135deg, #1a1a28 0%, #0f0f15 100%);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 1rem;
        padding: 1.75rem;
        overflow: hidden;
        transition: transform 0.35s ease, box-shadow 0.35s ease;
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
    }
    .pred-card::before {
        content: "";
        position: absolute; top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, #e10600 0%, #e10600 55%, transparent 100%);
        box-shadow: 0 0 15px rgba(225,6,0,0.6);
    }
    .pred-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 0 30px rgba(225,6,0,0.35), 0 10px 30px rgba(0,0,0,0.6);
    }

    /* ── Driver row ──────────────────────────────────────── */
    .driver-line {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 0.75rem;
        background: rgba(255,255,255,0.03);
        border-radius: 0.5rem;
        border: 1px solid rgba(255,255,255,0.05);
    }
    .pos-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2rem; height: 2rem;
        border-radius: 9999px;
        font-weight: 800;
        font-size: 0.85rem;
        font-family: "Audiowide", sans-serif;
        border: 2px solid rgba(255,255,255,0.15);
        background: rgba(0,0,0,0.35);
        color: white;
        flex-shrink: 0;
    }
    .pos-1 { background: linear-gradient(135deg, #facc15 0%, #b45309 100%); color: #000; border-color: rgba(250,204,21,0.6); }
    .pos-2 { background: linear-gradient(135deg, #e5e7eb 0%, #6b7280 100%); color: #000; border-color: rgba(229,231,235,0.6); }
    .pos-3 { background: linear-gradient(135deg, #fb923c 0%, #9a3412 100%); color: #000; border-color: rgba(251,146,60,0.6); }

    /* ── Status pill ─────────────────────────────────────── */
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.8rem;
        border-radius: 9999px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
    }
    .status-open   { background: rgba(225,6,0,0.15); border: 1px solid rgba(225,6,0,0.5); color: #fca5a5; }
    .status-locked { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.2); color: rgba(255,255,255,0.55); }

    /* ── Buttons ─────────────────────────────────────────── */
    .btn-f1 {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
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
    .btn-f1::after { content: "→"; transition: transform 0.25s ease; }
    .btn-f1:hover::after { transform: translateX(4px); }

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
        .reveal, .reveal-scale { opacity: 1 !important; transform: none !important; transition: none !important; }
    }
</style>

{{-- ───────────────────────── HERO ───────────────────────── --}}
<section class="preds-hero text-white">
    <div class="hero-stripe top"></div>
    <div class="hero-stripe bottom"></div>

    <div class="relative z-10 max-w-5xl mx-auto px-4 py-12 text-center">
        <div class="reveal-scale">
            <p class="audiowide-regular text-xs md:text-sm text-white/60 tracking-[6px] mb-2">
                YOUR PREDICTIONS
            </p>
            <h2 class="audiowide-regular text-4xl md:text-5xl lg:text-6xl font-bold mb-3">
                My <span class="accent">Predictions</span>
            </h2>
            <p class="text-gray-300 text-base md:text-lg">
                Every podium you've called — edit while the race is still open.
            </p>
        </div>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-white audiowide-regular">

    @if($predictions->isEmpty())
        <div class="bg-white/5 border border-white/10 rounded-xl p-16 text-center max-w-2xl mx-auto">
            <p class="text-gray-400 mb-6 text-lg">You haven't made any predictions yet.</p>
            <a href="{{ route('game.index') }}" class="btn-f1">Start Predicting</a>
        </div>
    @else
        <div class="flex flex-wrap justify-center gap-6">
            @foreach($predictions as $pred)
                @php
                    $isOpen = $pred->raceDate && \Carbon\Carbon::parse($pred->raceDate)->isFuture();
                @endphp
                <div class="pred-card reveal flex flex-col w-full sm:w-[22rem] lg:w-[24rem]">
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div class="min-w-0">
                            <p class="text-[0.65rem] text-white/50 tracking-[3px] uppercase mb-1">
                                Round {{ $pred->round }} · {{ $pred->season }}
                            </p>
                            <h3 class="text-xl md:text-2xl font-bold text-white break-words leading-tight">
                                {{ strtoupper($pred->raceName) }}
                            </h3>
                            @if($pred->raceDate)
                                <p class="text-xs text-white/50 mt-1">
                                    {{ \Carbon\Carbon::parse($pred->raceDate)->format('D, d M Y') }}
                                </p>
                            @endif
                        </div>

                        <span class="status-pill {{ $isOpen ? 'status-open' : 'status-locked' }} shrink-0">
                            {{ $isOpen ? '● Open' : '✕ Locked' }}
                        </span>
                    </div>

                    <p class="text-[0.7rem] text-white/50 uppercase tracking-[3px] mb-2">Your Podium Order</p>
                    <div class="space-y-1.5 mb-5 flex-1">
                        @foreach($pred->formatted_order as $driverLine)
                            @php
                                $parts = explode('. ', $driverLine, 2);
                                $position = (int) ($parts[0] ?? 0);
                                $driverName = $parts[1] ?? $driverLine;
                            @endphp
                            <div class="driver-line">
                                <span class="pos-badge pos-{{ $position }}">{{ $position ?: '?' }}</span>
                                <span class="text-sm text-white/90 truncate">{{ $driverName }}</span>
                            </div>
                        @endforeach
                    </div>

                    @if($isOpen)
                        <a href="{{ route('game.play', ['season' => $pred->season, 'round' => $pred->round]) }}"
                           class="btn-f1 w-full mt-auto">
                            Edit Prediction
                        </a>
                    @else
                        <div class="text-center text-xs text-white/40 italic py-2 mt-auto">
                            Race already completed
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-12 text-center">
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
