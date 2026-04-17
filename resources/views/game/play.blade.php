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
    .play-hero {
        position: relative;
        overflow: hidden;
        background:
            radial-gradient(ellipse at 50% 20%, rgba(225,6,0,0.25) 0%, transparent 55%),
            linear-gradient(135deg, #0a0a0f 0%, #15151e 60%, #0a0a0f 100%);
    }
    .play-hero::before {
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

    .play-hero h2 {
        letter-spacing: 3px;
        text-shadow: 0 0 30px rgba(225,6,0,0.35);
    }
    .play-hero h2 .accent {
        color: #e10600;
        text-shadow: 0 0 20px rgba(225,6,0,0.8);
    }

    /* ── Prediction form ─────────────────────────────────── */
    .prediction-row {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem 1rem;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 1rem;
        transition: border-color 0.25s ease, background 0.25s ease;
    }
    .prediction-row:hover {
        border-color: rgba(225,6,0,0.35);
        background: rgba(225,6,0,0.05);
    }

    .pos-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.75rem; height: 2.75rem;
        border-radius: 9999px;
        font-weight: 800;
        font-size: 1rem;
        font-family: "Audiowide", sans-serif;
        border: 2px solid rgba(255,255,255,0.15);
        background: rgba(0,0,0,0.35);
        color: white;
        flex-shrink: 0;
    }
    .pos-1 { background: linear-gradient(135deg, #facc15 0%, #b45309 100%); color: #000; border-color: rgba(250,204,21,0.6); }
    .pos-2 { background: linear-gradient(135deg, #e5e7eb 0%, #6b7280 100%); color: #000; border-color: rgba(229,231,235,0.6); }
    .pos-3 { background: linear-gradient(135deg, #fb923c 0%, #9a3412 100%); color: #000; border-color: rgba(251,146,60,0.6); }

    .driver-select {
        flex: 1;
        padding: 0.85rem 1rem;
        background: rgba(0,0,0,0.4);
        border: 1px solid rgba(255,255,255,0.2);
        color: white;
        border-radius: 0.5rem;
        transition: border-color 0.25s ease, box-shadow 0.25s ease;
        cursor: pointer;
    }
    .driver-select:focus {
        outline: none;
        border-color: rgba(225,6,0,0.9);
        box-shadow: 0 0 15px rgba(225,6,0,0.35);
    }
    .driver-select option { background: #15151e; color: white; }

    /* ── Buttons ─────────────────────────────────────────── */
    .btn-f1 {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 1rem 2rem;
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
        justify-content: center;
        gap: 0.5rem;
        padding: 1rem 2rem;
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
<section class="play-hero text-white">
    <div class="hero-stripe top"></div>
    <div class="hero-stripe bottom"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 py-12 text-center">
        <div class="reveal-scale">
            <p class="audiowide-regular text-xs md:text-sm text-white/60 tracking-[6px] mb-2">
                MAKE YOUR PREDICTION
            </p>
            <h2 class="audiowide-regular text-3xl md:text-4xl lg:text-5xl font-bold mb-3">
                {{ $raceName }} <span class="accent">Podium</span>
            </h2>
            <p class="text-gray-300 text-base md:text-lg">
                Place every driver in their finishing position. Correct guesses = more points.
            </p>
        </div>
    </div>
</section>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-white audiowide-regular">

    <form method="POST" action="{{ route('game.storePrediction') }}" id="guess-form" class="space-y-3">
        @csrf
        <input type="hidden" name="season" value="{{ $season }}">
        <input type="hidden" name="round" value="{{ $round }}">
        <input type="hidden" name="player_name" value="{{ Auth::user()->name }}">

        <div id="error-message"
             class="hidden bg-red-900/30 border border-red-500/50 text-red-300 p-3 rounded-lg text-center font-bold mb-4">
        </div>

        @for($pos = 1; $pos <= $drivers->count(); $pos++)
            <div class="prediction-row reveal">
                <span class="pos-badge pos-{{ $pos }}">{{ $pos }}</span>

                <select name="positions[{{ $pos }}]" class="driver-select" data-pos="{{ $pos }}">
                    <option value="">-- Select Driver --</option>
                    @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}"
                            {{ isset($savedOrder[$pos-1]) && $savedOrder[$pos-1] == $driver->id ? 'selected' : '' }}>
                            {{ $driver->given_name }} {{ $driver->family_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endfor

        <div class="pt-4 flex flex-col sm:flex-row gap-3">
            <a href="{{ route('game.index') }}" class="btn-ghost w-full sm:w-auto">
                ← Back
            </a>
            <button type="submit" class="btn-f1 w-full sm:flex-1">
                Save Prediction
            </button>
        </div>
    </form>
</div>

<script>
(function(){
    // All drivers passed from server
    const drivers = @json($drivers->map(fn($d) => ['id' => (string) $d->id, 'name' => trim($d->given_name . ' ' . $d->family_name)])->values());
    const selects = Array.from(document.querySelectorAll('select[name^="positions"]'));

    // Rebuild options so each select only shows drivers not chosen elsewhere
    // (plus the driver currently selected in that specific select).
    function refresh() {
        const chosen = new Set();
        selects.forEach(s => { if (s.value) chosen.add(s.value); });

        selects.forEach(s => {
            const current = s.value;
            // Clear
            s.innerHTML = '';
            // Placeholder
            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = '-- Select Driver --';
            if (!current) placeholder.selected = true;
            s.appendChild(placeholder);
            // Drivers
            drivers.forEach(d => {
                if (d.id === current || !chosen.has(d.id)) {
                    const opt = document.createElement('option');
                    opt.value = d.id;
                    opt.textContent = d.name;
                    if (d.id === current) opt.selected = true;
                    s.appendChild(opt);
                }
            });
        });
    }

    selects.forEach(s => s.addEventListener('change', refresh));
    refresh();

    // Client-side validation
    document.getElementById('guess-form').addEventListener('submit', function(e) {
        const chosen = new Set();
        let error = '';
        for (const s of selects) {
            if (!s.value) { error = 'Please select a driver for every position.'; break; }
            if (chosen.has(s.value)) { error = 'Each driver can only be assigned to one position.'; break; }
            chosen.add(s.value);
        }
        if (error) {
            e.preventDefault();
            const box = document.getElementById('error-message');
            box.textContent = error;
            box.classList.remove('hidden');
            box.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
})();

// Scroll reveal
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
