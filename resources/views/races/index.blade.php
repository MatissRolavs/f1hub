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
        left: 0; top: 8%; bottom: 8%;
        width: 6px;
        background: #e10600;
        box-shadow: 0 0 12px rgba(225,6,0,0.6);
    }

    /* ── Hero ─────────────────────────────────────────────── */
    .races-hero {
        position: relative;
        overflow: hidden;
        background:
            radial-gradient(ellipse at 50% 20%, rgba(225,6,0,0.25) 0%, transparent 55%),
            linear-gradient(135deg, #0a0a0f 0%, #15151e 60%, #0a0a0f 100%);
    }
    .races-hero::before {
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

    .races-hero h2 {
        letter-spacing: 4px;
        text-shadow: 0 0 30px rgba(225,6,0,0.35);
    }
    .races-hero h2 .accent {
        color: #e10600;
        text-shadow: 0 0 20px rgba(225,6,0,0.8);
    }

    /* ── Season selector ─────────────────────────────────── */
    .season-select {
        background: rgba(255,255,255,0.08);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.25);
        color: white;
        padding: 0.65rem 1.25rem;
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

    /* ── Card red glow on hover ──────────────────────────── */
    .f1-card {
        transition: transform 0.35s ease, box-shadow 0.35s ease;
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
    }
    .f1-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 0 30px rgba(225,6,0,0.45), 0 10px 30px rgba(0,0,0,0.6);
    }

    /* ── Modal action buttons ────────────────────────────── */
    .btn { display:inline-flex; align-items:center; gap:0.4rem; padding:0.5rem 1rem; border-radius:0.5rem; font-weight:700; letter-spacing:1px; }
    .btn-green { background:#10B981; color:white; }
    .btn-green:hover { background:#059669; }
    .btn-gray  { background:#4B5563; color:white; }
    .btn-gray:hover  { background:#6B7280; }

    @media (prefers-reduced-motion: reduce) {
        .reveal, .reveal-scale { opacity: 1 !important; transform: none !important; transition: none !important; }
    }
</style>

{{-- ───────────────────────── HERO ───────────────────────── --}}
<section class="races-hero min-h-[620px] text-white">
    <div class="hero-stripe top"></div>
    <div class="hero-stripe bottom"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 py-14">
        @if(session('success'))
            <div class="bg-green-600/20 border border-green-500/40 text-green-200 p-4 rounded-lg mb-8 text-center font-semibold">
                {{ session('success') }}
            </div>
        @endif

        {{-- Animated 3D Track (kept from previous version) --}}
        <div id="animated-track" class="hidden md:flex justify-center items-center py-4 perspective-1000 overflow-visible">
            <div id="trackWrapper"
                 class="transform-style-preserve-3d"
                 style="transform: rotateX(60deg) scale(2.5); transform-origin: center;">
                <svg viewBox="0 0 600 400" width="600" height="200" class="relative" id="trackSvg">
                    <path d="m490.05 299.77c16.808-24.592 0.66497-57.836-34.045-51.106-11.377 2.206-14.444 8.6956-10.241 21.672 4.399 13.579 4.8848 13.383-29.629 11.999-15.386-0.61702-36.964-1.3708-47.95-1.6751-27.435-0.75994-87.181-3.7741-91.736-4.6278-4.338-0.81306-4.9747-1.9562-10.912-19.566-6.0748-18.017-3.1485-20.098 24.324-17.289 44.704 4.5716 57.606-20.625 31.837-62.181-17.295-27.89-17.268-47.174 0.0895-64.49 13.139-13.107 19.257-14.122 66.864-11.084 42.15 2.6897 56.859 1.5632 70.668-5.4123 21.398-10.809 15.882-15.451-33.644-28.321-44.524-11.57-42.802-11.381-67.703-7.4009-10.66 1.704-25.959 3.6865-33.999 4.4068-8.0398 0.72029-21.057 2.3314-28.926 3.5785-26.213 4.1539-34.42 1.8511-42.539-11.933-10.271-17.439 2.7058-28.334 16.173-13.577 11.201 12.273 18.447 12.455 26.19 0.66263 12.229-18.623-9.9568-43.345-34.793-38.772-16.038 2.9532-25.482 13.222-33.781 36.736-2.7239 7.7175-5.6328 15.738-6.464 17.824-0.83113 2.0862-14.984 41.244-31.451 87.017-50.781 141.15-51.54 143.21-53.474 145-2.8829 2.6772-2.1578 2.6053-55.675 5.4587-57.364 3.0585-61.411 3.8371-72.32 13.905-1.2497 1.1533-2.4682 2.3098-2.707 2.5708-0.59522 0.65072 1.4105 5.0164 3.2039 6.9734 1.9529 2.1311-1.4502 2.062 59.441 1.2134 48.318-0.67342 86.183-0.70049 132.01-0.0929 16.254 0.21548 32.574 0.22163 125.88 0.0461 146.88-0.27627 137.29 0.2191 150.45-7.7817 6.4561-3.9238 11.364-8.6537 14.852-13.758z"
                          fill="none" stroke="white" stroke-width="4" id="shanghaiPath" stroke-linecap="round"/>
                    <rect class="car" x="0" y="0" width="12" height="5" fill="red" rx="4" ry="4"/>
                </svg>
            </div>
        </div>

        {{-- Hero text + selector --}}
        <div class="reveal-scale text-center max-w-4xl mx-auto mt-4">
            <p class="audiowide-regular text-sm md:text-base text-white/60 tracking-[6px] mb-2">
                {{ $selectedSeason }} SEASON
            </p>
            <h2 id="featuredHeading" class="audiowide-regular text-4xl md:text-6xl lg:text-7xl font-bold mb-4">
                Formula <span class="accent">1</span> Calendar
            </h2>
            <p class="text-base md:text-lg text-gray-300 mb-8 max-w-2xl mx-auto">
                The full race schedule, track stats, and podium finishers — all in one place.
            </p>

            <form method="GET" action="{{ route('races.index') }}" class="flex items-center justify-center gap-3">
                <label for="season" class="text-xs sm:text-sm text-white/70 audiowide-regular uppercase tracking-widest">Season</label>
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
</section>

{{-- ───────────────────────── FEATURED ───────────────────────── --}}
<section class="w-full bg-gray-900 text-white py-16">
    <div class="max-w-7xl mx-auto px-4">
        <h3 class="section-title audiowide-regular text-2xl md:text-3xl font-bold uppercase mb-8 reveal">
            Featured Races
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($featuredRaces as $race)
                <div class="reveal">
                    @if($race['label'])
                        <p class="text-xl font-semibold text-white mb-2 audiowide-regular">
                            {{ $race['label'] }}
                        </p>
                    @endif
                    @include('races.partials.card', ['race' => $race, 'showTop3' => false])
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ───────────────────────── ALL RACES ───────────────────────── --}}
<section class="w-full bg-gradient-to-b from-gray-900 via-[#0f0f15] to-gray-900 text-white py-16">
    <div class="max-w-7xl mx-auto px-4">
        <h3 class="section-title audiowide-regular text-2xl md:text-3xl font-bold uppercase mb-8 reveal">
            Full {{ $selectedSeason }} Schedule
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($allRaces as $race)
                @include('races.partials.card', ['race' => $race, 'showTop3' => true])
            @endforeach
        </div>
    </div>
</section>

{{-- Modal --}}
<div id="raceModalOverlay" class="fixed inset-0 bg-black bg-opacity-80 hidden items-center justify-center z-50 backdrop-blur-sm">
    <div id="raceModal" class="bg-gradient-to-br from-[#1a1a28] to-[#0f0f15] border border-white/10 text-white rounded-2xl w-full max-w-3xl p-6 relative shadow-2xl">
        <button class="absolute top-3 right-4 text-3xl text-white/60 hover:text-white transition" id="closeModal">&times;</button>
        <h3 id="modalTitle" class="text-2xl font-bold mb-4 audiowide-regular"></h3>

        <div id="modalTrack" class="w-full flex justify-center mb-4"></div>

        <div id="modalInfo" class="space-y-1 text-gray-200"></div>
        <div id="modalActions" class="mt-5 flex justify-end gap-2"></div>
    </div>
</div>

<script>
    const modalOverlay  = document.getElementById('raceModalOverlay');
    const closeModalBtn = document.getElementById('closeModal');
    const modalTitle    = document.getElementById('modalTitle');
    const modalTrack    = document.getElementById('modalTrack');
    const modalInfo     = document.getElementById('modalInfo');
    const modalActions  = document.getElementById('modalActions');

    function openModal(data) {
        modalTitle.textContent = data.name;
        modalTrack.innerHTML = '';

        const trackSlug = data.name.toLowerCase().replace(/\s+/g, '');
        fetch(`/images/tracks/${trackSlug}.svg`)
            .then(res => {
                if (!res.ok) throw new Error("Track not found");
                return res.text();
            })
            .then(svg => {
                modalTrack.innerHTML = `
                    <div class="flex justify-center items-center perspective-1000 w-full">
                        <div class="transform-style-preserve-3d w-full max-w-[300px] mx-auto"
                             style="transform: rotateX(60deg) scale(1.8); transform-origin: center;"
                             id="modalTrackWrapper">
                            <div class="w-full max-w-full overflow-visible">
                                ${svg}
                            </div>
                        </div>
                    </div>
                `;

                const svgElement = modalTrack.querySelector('svg');
                if (svgElement) {
                    svgElement.removeAttribute('width');
                    svgElement.removeAttribute('height');
                    svgElement.setAttribute('class', 'w-full h-auto');
                }

                const path = svgElement?.querySelector('path');
                if (path) {
                    const pathLength = path.getTotalLength();

                    path.setAttribute('stroke', 'white');
                    path.setAttribute('stroke-opacity', '0.3');
                    path.setAttribute('stroke-width', '3');
                    path.setAttribute('fill', 'none');

                    const brightPath = path.cloneNode();
                    brightPath.setAttribute('stroke', '#e10600');
                    brightPath.setAttribute('stroke-opacity', '1');
                    brightPath.setAttribute('stroke-width', '3');
                    brightPath.setAttribute('stroke-dasharray', pathLength);
                    brightPath.setAttribute('stroke-dashoffset', pathLength);
                    brightPath.setAttribute('fill', 'none');
                    path.parentNode.appendChild(brightPath);

                    const car2 = document.createElementNS("http://www.w3.org/2000/svg", "rect");
                    car2.setAttribute('width', '14');
                    car2.setAttribute('height', '6');
                    car2.setAttribute('fill', '#e10600');
                    car2.setAttribute('rx', '3');
                    car2.setAttribute('ry', '3');
                    car2.classList.add('car2');
                    path.parentNode.appendChild(car2);

                    const motionPath = anime.path(path);
                    anime({
                        targets: '.car2',
                        translateX: motionPath('x'),
                        translateY: motionPath('y'),
                        rotate: motionPath('angle'),
                        duration: 9000,
                        easing: 'linear',
                        loop: true,
                        update: function(anim) {
                            const progress = anim.progress / 100;
                            brightPath.setAttribute('stroke-dashoffset', pathLength - (progress * pathLength));
                        }
                    });

                    anime({
                        targets: '#modalTrackWrapper',
                        rotateZ: '-360deg',
                        duration: 25000,
                        easing: 'linear',
                        loop: true
                    });
                }
            })
            .catch(() => {});

        modalInfo.innerHTML = `
            <p><strong>Date:</strong> ${data.date}</p>
            <p><strong>Location:</strong> ${data.location}</p>
            <p><strong>Status:</strong> ${data.status}</p>
            ${data.length     ? `<p><strong>Track Length:</strong> ${data.length}</p>` : ''}
            ${data.turns      ? `<p><strong>Turns:</strong> ${data.turns}</p>` : ''}
            ${data.lapRecord  ? `<p><strong>Lap Record:</strong> ${data.lapRecord}</p>` : ''}
            ${data.description ? `<p><strong>About:</strong> ${data.description}</p>` : ''}
        `;

        modalActions.innerHTML = '';
        if (data.status === 'Completed') {
            const resultsBtn = document.createElement('a');
            resultsBtn.href = data.resultsUrl;
            resultsBtn.textContent = 'View Results';
            resultsBtn.className = 'btn btn-green';
            modalActions.appendChild(resultsBtn);
        }
        const closeBtn = document.createElement('button');
        closeBtn.textContent = 'Close';
        closeBtn.className = 'btn btn-gray';
        closeBtn.addEventListener('click', closeModal);
        modalActions.appendChild(closeBtn);

        modalOverlay.classList.remove('hidden');
        modalOverlay.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modalOverlay.classList.add('hidden');
        modalOverlay.classList.remove('flex');
        document.body.style.overflow = '';
    }

    closeModalBtn.addEventListener('click', closeModal);
    modalOverlay.addEventListener('click', (e) => { if (e.target === modalOverlay) closeModal(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });

    document.querySelectorAll('.race-card').forEach(card => {
        card.addEventListener('click', () => {
            openModal({
                name: card.dataset.name,
                date: card.dataset.date,
                location: card.dataset.location,
                status: card.dataset.status,
                img: card.dataset.img,
                length: card.dataset.length,
                turns: card.dataset.turns,
                lapRecord: card.dataset.lapRecord,
                description: card.dataset.description,
                resultsUrl: card.dataset.resultsUrl
            });
        });
    });
</script>

{{-- Anime.js (single include) --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>

{{-- 3D hero track animation --}}
<script>
(function(){
    const path = document.querySelector('#shanghaiPath');
    if (!path) return;
    const pathLength = path.getTotalLength();

    path.setAttribute('stroke', 'white');
    path.setAttribute('stroke-opacity', '0.3');
    path.setAttribute('stroke-width', '3');

    const brightPath = path.cloneNode();
    brightPath.setAttribute('stroke', '#e10600');
    brightPath.setAttribute('stroke-opacity', '1');
    brightPath.setAttribute('stroke-width', '3');
    brightPath.setAttribute('stroke-dasharray', pathLength);
    brightPath.setAttribute('stroke-dashoffset', pathLength);
    path.parentNode.appendChild(brightPath);

    const motionPath = anime.path('#shanghaiPath');
    anime({
        targets: '.car',
        translateX: motionPath('x'),
        translateY: motionPath('y'),
        rotate: motionPath('angle'),
        duration: 9000,
        easing: 'linear',
        loop: true,
        update: function(anim) {
            const progress = anim.progress / 100;
            brightPath.setAttribute('stroke-dashoffset', pathLength - (progress * pathLength));
        }
    });

    anime({
        targets: '#trackWrapper',
        rotateZ: '-360deg',
        duration: 25000,
        easing: 'linear',
        loop: true
    });
})();
</script>

{{-- Scroll-reveal observer (pure CSS reveal classes) --}}
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
