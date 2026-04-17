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
    .drivers-hero {
        position: relative;
        width: 100%;
        height: 44rem;
        overflow: hidden;
    }
    .drivers-hero-overlay {
        position: absolute; inset: 0;
        background:
            radial-gradient(ellipse at 50% 30%, rgba(225,6,0,0.35) 0%, transparent 55%),
            linear-gradient(to bottom, rgba(10,10,15,0.45) 0%, rgba(10,10,15,0.85) 100%);
    }
    .drivers-hero::before {
        content: "";
        position: absolute; inset: 0;
        background-image: repeating-linear-gradient(
            45deg,
            rgba(255,255,255,0.025) 0 14px,
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

    .drivers-hero h2 {
        letter-spacing: 4px;
        text-shadow: 0 0 30px rgba(225,6,0,0.35);
    }
    .drivers-hero h2 .accent {
        color: #e10600;
        text-shadow: 0 0 20px rgba(225,6,0,0.8);
    }

    /* ── Card red glow on hover ──────────────────────────── */
    .f1-card {
        transition: transform 0.35s ease, box-shadow 0.35s ease;
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
    }
    .f1-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 0 35px rgba(225,6,0,0.5), 0 10px 30px rgba(0,0,0,0.6);
    }

    /* ── Season selector ─────────────────────────────────── */
    .season-select {
        background: rgba(255,255,255,0.08);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.25);
        color: white;
        padding: 0.75rem 1.25rem;
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

    @media (prefers-reduced-motion: reduce) {
        .reveal, .reveal-scale { opacity: 1 !important; transform: none !important; transition: none !important; }
    }
</style>

{{-- ───────────────────────── HERO ───────────────────────── --}}
<div class="drivers-hero">
    <img src="{{ asset('images/racetrack.jpg') }}"
         alt="Racetrack Banner"
         class="absolute inset-0 w-full h-full object-cover">

    <div class="drivers-hero-overlay"></div>
    <div class="hero-stripe top"></div>
    <div class="hero-stripe bottom"></div>

    <div class="relative z-10 h-full flex flex-col justify-center items-center text-center px-4 pb-64">
        <div class="reveal-scale">
            <p class="audiowide-regular text-xl md:text-2xl text-white/70 tracking-[6px] mb-2">
                {{ $selectedSeason }} SEASON
            </p>
            <h2 class="audiowide-regular text-4xl md:text-6xl lg:text-7xl font-bold mb-6 text-white">
                FORMULA <span class="accent">1</span> DRIVERS
            </h2>
            <p class="max-w-2xl text-base md:text-lg text-gray-300 mb-8 mx-auto">
                Meet the grid of the {{ $selectedSeason }} Formula&nbsp;1 World Championship.
            </p>

            <form method="GET" action="{{ route('drivers.index') }}" class="flex items-center justify-center gap-3">
                <label for="season" class="text-sm text-white/70 audiowide-regular uppercase tracking-widest">Season</label>
                <select name="season" id="season" onchange="this.form.submit()" class="season-select audiowide-regular">
                    @foreach($seasons as $season)
                        <option value="{{ $season }}" {{ $season == $selectedSeason ? 'selected' : '' }}>
                            {{ $season }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
</div>

{{-- ───────────────────────── CARDS ───────────────────────── --}}
<div class="bg-gray-900 pb-20">
    <div class="max-w-7xl mx-auto px-4">
        @if(session('success'))
            <div class="bg-green-600/20 border border-green-500/40 text-green-200 p-4 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($drivers->count())
            {{-- Podium row: top 3 overlapping the hero --}}
            <div class="relative -mt-[18rem] z-20">
                <h3 class="section-title audiowide-regular text-2xl md:text-3xl font-bold uppercase text-white mb-6 reveal">
                    Championship Podium
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach($drivers->take(3) as $driver)
                        @if(!$driver->standings->first()) @continue @endif
                        @include('drivers.partials.card', ['driver' => $driver, 'selectedSeason' => $selectedSeason])
                    @endforeach
                </div>
            </div>

            {{-- Rest of the grid --}}
            @if($drivers->count() > 3)
                <div class="mt-16">
                    <h3 class="section-title audiowide-regular text-2xl md:text-3xl font-bold uppercase text-white mb-6 reveal">
                        The Rest of the Grid
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        @foreach($drivers->skip(3) as $driver)
                            @if(!$driver->standings->first()) @continue @endif
                            @include('drivers.partials.card', ['driver' => $driver, 'selectedSeason' => $selectedSeason])
                        @endforeach
                    </div>
                </div>
            @endif
        @else
            <div class="text-center py-24">
                <p class="text-gray-400 text-lg">
                    No drivers found.
                    <a href="{{ route('drivers.sync') }}" class="text-red-500 hover:text-red-400 underline">Sync now</a>
                </p>
            </div>
        @endif
    </div>
</div>

<script>
// Scroll-reveal observer
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
