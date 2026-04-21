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
    .reveal-stagger.is-visible > *:nth-child(1)  { transition-delay: 0s; }
    .reveal-stagger.is-visible > *:nth-child(2)  { transition-delay: 0.08s; }
    .reveal-stagger.is-visible > *:nth-child(3)  { transition-delay: 0.16s; }
    .reveal-stagger.is-visible > *:nth-child(n+4){ transition-delay: 0.24s; }

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
    .game-hero {
        position: relative;
        overflow: hidden;
        background:
            radial-gradient(ellipse at 50% 20%, rgba(225,6,0,0.25) 0%, transparent 55%),
            linear-gradient(135deg, #0a0a0f 0%, #15151e 60%, #0a0a0f 100%);
    }
    .game-hero::before {
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

    .game-hero h2 {
        letter-spacing: 4px;
        text-shadow: 0 0 30px rgba(225,6,0,0.35);
    }
    .game-hero h2 .accent {
        color: #e10600;
        text-shadow: 0 0 20px rgba(225,6,0,0.8);
    }

    /* ── Race block (original layout restored) ───────────── */
    .race-block {
        position: relative;
        padding: 2.5rem 1rem;
    }
    .race-block .bg-formula {
        position: absolute;
        inset: 0;
        object-fit: contain;
        opacity: 0.2;
        pointer-events: none;
    }
    .race-title {
        font-weight: 700;
        letter-spacing: 0.15em;
        text-decoration-line: underline;
        text-decoration-color: #fff;
        text-underline-offset: 8px;
    }

    /* ── Countdown boxes (big, glowing, original style) ──── */
    .countdown-box {
        background: transparent;
        backdrop-filter: blur(4px);
        border: 2px solid #fff;
        border-radius: 0.5rem;
        box-shadow: 0 0 10px rgba(255,255,255,0.8), 0 0 25px rgba(0,0,0,1);
        font-weight: 800;
        color: #fff;
    }

    /* ── Primary red CTA button (shared across views) ────── */
    .btn-f1 {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.9rem 1.75rem;
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

    /* ── Ghost button (matches other views) ──────────────── */
    .btn-ghost {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.9rem 1.75rem;
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

    /* ── Podium ──────────────────────────────────────────── */
    .podium-card {
        position: relative;
        padding: 1.75rem 1.5rem;
        border-radius: 1rem;
        text-align: center;
        overflow: hidden;
        transition: transform 0.35s ease, box-shadow 0.35s ease;
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
    }
    .podium-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 0 30px rgba(225,6,0,0.4), 0 10px 30px rgba(0,0,0,0.6);
    }
    .podium-card .pos-medal {
        position: absolute;
        top: 1rem; left: 50%; transform: translateX(-50%);
        width: 3.5rem; height: 3.5rem;
        border-radius: 9999px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 1.5rem;
        border: 3px solid rgba(0,0,0,0.4);
        z-index: 2;
    }
    .medal-1 { background: linear-gradient(135deg, #facc15 0%, #b45309 100%); color: #000; }
    .medal-2 { background: linear-gradient(135deg, #e5e7eb 0%, #6b7280 100%); color: #000; }
    .medal-3 { background: linear-gradient(135deg, #fb923c 0%, #9a3412 100%); color: #000; }

    .podium-card.first  { background: linear-gradient(180deg, rgba(250,204,21,0.2) 0%, rgba(180,83,9,0.3) 100%); border: 1px solid rgba(250,204,21,0.4); min-height: 250px; }
    .podium-card.second { background: linear-gradient(180deg, rgba(229,231,235,0.15) 0%, rgba(107,114,128,0.3) 100%); border: 1px solid rgba(229,231,235,0.3); min-height: 220px; }
    .podium-card.third  { background: linear-gradient(180deg, rgba(251,146,60,0.15) 0%, rgba(154,52,18,0.3) 100%); border: 1px solid rgba(251,146,60,0.3); min-height: 200px; }

    /* ── Leaderboard table ───────────────────────────────── */
    .lb-table { width: 100%; border-collapse: separate; border-spacing: 0 6px; }
    .lb-table thead th {
        background: transparent;
        color: rgba(255,255,255,0.5);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 2px;
        padding: 0.75rem 1rem;
        text-align: left;
    }
    .lb-row {
        background: rgba(255,255,255,0.03);
        transition: background 0.25s ease;
    }
    .lb-row:hover { background: rgba(225,6,0,0.1); }
    .lb-row td {
        padding: 0.9rem 1rem;
        color: white;
        border-top: 1px solid rgba(255,255,255,0.06);
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .lb-row td:first-child { border-left: 1px solid rgba(255,255,255,0.06); border-top-left-radius: 0.5rem; border-bottom-left-radius: 0.5rem; }
    .lb-row td:last-child  { border-right: 1px solid rgba(255,255,255,0.06); border-top-right-radius: 0.5rem; border-bottom-right-radius: 0.5rem; }

    @media (prefers-reduced-motion: reduce) {
        .reveal, .reveal-scale, .reveal-stagger > * {
            opacity: 1 !important; transform: none !important; transition: none !important;
        }
        .countdown-box { animation: none; }
    }
</style>

{{-- ───────────────────────── HERO ───────────────────────── --}}
<section class="game-hero min-h-[360px] text-white">
    <div class="hero-stripe top"></div>
    <div class="hero-stripe bottom"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 py-14 text-center">
        <div class="reveal-scale">
            <p class="audiowide-regular text-xs md:text-sm text-white/60 tracking-[6px] mb-2">
                PLAY THE GAME
            </p>
            <h2 class="audiowide-regular text-4xl md:text-5xl lg:text-6xl font-bold mb-4">
                Prediction <span class="accent">Game</span>
            </h2>
            <p class="text-gray-300 max-w-2xl mx-auto text-base md:text-lg">
                Predict the podium, score points, and battle other fans for the top of the leaderboard.
            </p>
        </div>
    </div>
</section>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-white audiowide-regular">

    {{-- ───────────────────────── UPCOMING RACES ───────────────────────── --}}
    @if(count($races) === 0)
        <div class="bg-white/5 border border-white/10 rounded-xl p-10 text-center mb-16">
            <p class="text-gray-400">No upcoming races to predict right now.</p>
            <div class="mt-5 flex justify-center gap-3 flex-wrap">
                <a href="{{ route('game.myPredictions') }}" class="btn-ghost">My Predictions</a>
                <a href="{{ route('game.predictionResults') }}" class="btn-ghost">My Results</a>
            </div>
        </div>
    @else
        <div class="mb-16">
            @foreach($races as $race)
                <div class="race-block reveal mb-20">
                    {{-- Background F1 outline image --}}
                    <img src="{{ asset('images/formula.png') }}"
                         alt="F1 outline background"
                         class="bg-formula w-full h-full" />

                    <div class="relative z-10">
                        {{-- Header --}}
                        <div class="mb-12">
                            {{-- Desktop layout --}}
                            <div class="hidden sm:flex justify-center items-start relative">
                                <span class="rotate-180 [writing-mode:vertical-rl] text-sm tracking-widest text-gray-400 -mr-1 mt-1">
                                    {{ $race['round'] ?? '?' }} ROUND
                                </span>
                                <div class="inline-block text-center relative">
                                    <h2 class="race-title text-4xl md:text-5xl">
                                        {{ strtoupper($race['raceName']) }}
                                    </h2>
                                    <div class="absolute right-0 w-full font-bold text-right mt-2 text-base uppercase text-white">
                                        Next Round In
                                    </div>
                                </div>
                            </div>

                            {{-- Mobile layout --}}
                            <div class="block sm:hidden text-center">
                                <h2 class="race-title text-3xl">
                                    {{ strtoupper($race['raceName']) }}
                                </h2>
                                <div class="mt-3 flex flex-col items-center gap-1 text-xs uppercase">
                                    <span class="tracking-widest text-gray-400">
                                        {{ $race['round'] ?? '?' }} ROUND
                                    </span>
                                    <span class="font-bold text-white">Next Round In</span>
                                </div>
                            </div>
                        </div>

                        {{-- Countdown --}}
                        <div class="w-full mb-12">
                            <div class="flex flex-col sm:flex-row justify-center items-center gap-6 sm:gap-12">
                                @foreach(['days' => 'Days', 'hours' => 'Hours', 'minutes' => 'Minutes', 'seconds' => 'Seconds'] as $unit => $label)
                                    <div class="flex flex-col items-center">
                                        <span id="{{ $unit }}-{{ $loop->parent->index }}"
                                              class="countdown-box text-5xl sm:text-8xl
                                                     w-28 h-28 sm:w-48 sm:h-48 flex items-center justify-center">
                                            0
                                        </span>
                                        <span class="text-xs sm:text-sm uppercase text-gray-300 mt-2">{{ $label }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Prediction Button --}}
                        <a href="{{ route('game.play', ['season' => $race['season'], 'round' => $race['round']]) }}"
                           class="btn-f1 w-full mb-6 sm:mb-8">
                            Make Your Prediction
                        </a>

                        {{-- Footer Buttons --}}
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('game.myPredictions') }}" class="btn-ghost w-full sm:flex-1">
                                My Predictions
                            </a>
                            <a href="{{ route('game.predictionResults') }}" class="btn-ghost w-full sm:flex-1">
                                My Results
                            </a>
                        </div>
                    </div>
                </div>

                <script>
                    (function(){
                        const raceDate = new Date("{{ $race['date'] }}T{{ $race['time'] ?? '00:00:00Z' }}").getTime();
                        const daysEl    = document.getElementById("days-{{ $loop->index }}");
                        const hoursEl   = document.getElementById("hours-{{ $loop->index }}");
                        const minutesEl = document.getElementById("minutes-{{ $loop->index }}");
                        const secondsEl = document.getElementById("seconds-{{ $loop->index }}");

                        function update() {
                            const distance = raceDate - Date.now();
                            if (distance <= 0) {
                                daysEl.textContent = hoursEl.textContent = minutesEl.textContent = secondsEl.textContent = "0";
                                clearInterval(timer);
                                return;
                            }
                            daysEl.textContent    = Math.floor(distance / (1000 * 60 * 60 * 24));
                            hoursEl.textContent   = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            minutesEl.textContent = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            secondsEl.textContent = Math.floor((distance % (1000 * 60)) / 1000);
                        }
                        update();
                        const timer = setInterval(update, 1000);
                    })();
                </script>
            @endforeach
        </div>
    @endif

    {{-- ───────────────────────── LEADERBOARD ───────────────────────── --}}
    <h3 class="section-title text-2xl md:text-3xl font-bold uppercase mb-8 reveal">
        Leaderboard
    </h3>

    @if($leaderboard->isEmpty())
        <div class="bg-white/5 border border-white/10 rounded-xl p-10 text-center">
            <p class="text-gray-400">No scores yet. Be the first to make a prediction!</p>
        </div>
    @else
        {{-- Podium --}}
        <div class="reveal-stagger grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 items-end">
            @if(isset($leaderboard[1]))
                <div class="podium-card second md:order-1">
                    <div class="pos-medal medal-2">2</div>
                    <div class="mt-16">
                        <p class="text-lg font-bold text-white break-words">{{ $leaderboard[1]->player_name }}</p>
                        <p class="text-3xl font-extrabold text-white mt-2">{{ $leaderboard[1]->total_score }} <span class="text-sm font-medium opacity-70">pts</span></p>
                        <p class="text-xs text-white/60 mt-2">{{ $leaderboard[1]->races_played }} races</p>
                    </div>
                </div>
            @else
                <div class="md:order-1"></div>
            @endif

            @if(isset($leaderboard[0]))
                <div class="podium-card first md:order-2">
                    <div class="pos-medal medal-1">1</div>
                    <div class="mt-16">
                        <p class="text-xl font-bold text-white break-words">{{ $leaderboard[0]->player_name }}</p>
                        <p class="text-4xl font-extrabold text-white mt-2">{{ $leaderboard[0]->total_score }} <span class="text-sm font-medium opacity-70">pts</span></p>
                        <p class="text-xs text-white/70 mt-2">{{ $leaderboard[0]->races_played }} races</p>
                    </div>
                </div>
            @endif

            @if(isset($leaderboard[2]))
                <div class="podium-card third md:order-3">
                    <div class="pos-medal medal-3">3</div>
                    <div class="mt-16">
                        <p class="text-lg font-bold text-white break-words">{{ $leaderboard[2]->player_name }}</p>
                        <p class="text-3xl font-extrabold text-white mt-2">{{ $leaderboard[2]->total_score }} <span class="text-sm font-medium opacity-70">pts</span></p>
                        <p class="text-xs text-white/60 mt-2">{{ $leaderboard[2]->races_played }} races</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Rest --}}
        @if(count($leaderboard) > 3)
            <div class="overflow-x-auto">
                <table class="lb-table">
                    <thead>
                        <tr>
                            <th class="w-16">Rank</th>
                            <th>Player</th>
                            <th class="text-right">Score</th>
                            <th class="text-right hidden sm:table-cell">Races</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaderboard->slice(3, 7) as $index => $player)
                            <tr class="lb-row">
                                <td><span class="font-bold text-white/80">{{ $index + 1 }}</span></td>
                                <td class="font-bold">{{ $player->player_name }}</td>
                                <td class="text-right tabular-nums font-bold">{{ $player->total_score }}</td>
                                <td class="text-right tabular-nums hidden sm:table-cell text-white/70">{{ $player->races_played }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endif
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
