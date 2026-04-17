<x-app-layout>
<style>
    /* ── Scroll reveal primitives ─────────────────────────── */
    .reveal,
    .reveal-left,
    .reveal-right,
    .reveal-scale {
        opacity: 0;
        transition: opacity 0.9s cubic-bezier(.2,.65,.3,1), transform 0.9s cubic-bezier(.2,.65,.3,1);
        will-change: opacity, transform;
    }
    .reveal        { transform: translateY(40px); }
    .reveal-left   { transform: translateX(-40px); }
    .reveal-right  { transform: translateX(40px); }
    .reveal-scale  { transform: scale(0.94); }
    .is-visible    { opacity: 1 !important; transform: none !important; }

    /* Stagger children once the parent is visible */
    .reveal-children > * {
        opacity: 0;
        transform: translateY(40px);
        transition: opacity 0.7s cubic-bezier(.2,.65,.3,1), transform 0.7s cubic-bezier(.2,.65,.3,1);
    }
    .reveal-children.is-visible > *:nth-child(1) { transition-delay: 0s; }
    .reveal-children.is-visible > *:nth-child(2) { transition-delay: 0.12s; }
    .reveal-children.is-visible > *:nth-child(3) { transition-delay: 0.24s; }
    .reveal-children.is-visible > *:nth-child(4) { transition-delay: 0.36s; }
    .reveal-children.is-visible > * { opacity: 1; transform: none; }

    /* ── Section title with F1 red accent bar ─────────────── */
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
    .hero {
        position: relative;
        min-height: 78vh;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background:
            radial-gradient(ellipse at 50% 20%, rgba(225,6,0,0.25) 0%, transparent 60%),
            linear-gradient(135deg, #0a0a0f 0%, #15151e 60%, #0a0a0f 100%);
    }
    .hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image:
            repeating-linear-gradient(
                45deg,
                rgba(255,255,255,0.02) 0 14px,
                transparent 14px 28px
            );
        pointer-events: none;
    }
    .hero-stripe {
        position: absolute;
        left: 0;
        height: 4px;
        width: 100%;
        background: linear-gradient(90deg, #e10600 0%, #e10600 55%, transparent 100%);
        box-shadow: 0 0 20px rgba(225,6,0,0.8);
    }
    .hero-stripe.top    { top: 0; }
    .hero-stripe.bottom {
        bottom: 0;
        background: linear-gradient(270deg, #e10600 0%, #e10600 55%, transparent 100%);
    }
    .hero h1 {
        letter-spacing: 4px;
        text-shadow: 0 0 30px rgba(225,6,0,0.35);
    }
    .hero h1 .accent {
        color: #e10600;
        text-shadow: 0 0 20px rgba(225,6,0,0.8);
    }

    /* ── Card red glow on hover ───────────────────────────── */
    .f1-card {
        transition: transform 0.35s ease, box-shadow 0.35s ease;
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
    }
    .f1-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 0 30px rgba(225,6,0,0.45), 0 10px 30px rgba(0,0,0,0.6);
    }

    /* ── Countdown boxes pulse ────────────────────────────── */
    @keyframes countdown-pulse {
        0%, 100% { box-shadow: 0 0 10px rgba(255,255,255,0.5), 0 0 25px rgba(0,0,0,1); border-color: rgba(255,255,255,0.8); }
        50%      { box-shadow: 0 0 18px rgba(225,6,0,0.7), 0 0 35px rgba(0,0,0,1); border-color: rgba(225,6,0,0.9); }
    }
    .countdown-box { animation: countdown-pulse 3.2s ease-in-out infinite; }

    /* ── Red CTA button ───────────────────────────────────── */
    .btn-f1 {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.75rem;
        background: linear-gradient(90deg, #e10600 0%, #a30400 100%);
        border-radius: 0.5rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: white;
        overflow: hidden;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .btn-f1:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(225,6,0,0.55);
    }
    .btn-f1::after {
        content: "→";
        transition: transform 0.25s ease;
    }
    .btn-f1:hover::after { transform: translateX(4px); }

    /* Smooth respect for reduced motion */
    @media (prefers-reduced-motion: reduce) {
        .reveal, .reveal-left, .reveal-right, .reveal-scale,
        .reveal-children > * {
            opacity: 1 !important;
            transform: none !important;
            transition: none !important;
        }
        .countdown-box { animation: none; }
    }
</style>

<div class="bg-gray-900 font-mono text-white leading-[1.7]">

    {{-- ───────────────────────── HERO ───────────────────────── --}}
    <section class="hero">
        <div class="hero-stripe top"></div>
        <div class="hero-stripe bottom"></div>

        <div class="relative z-10 max-w-4xl px-6 text-center reveal-scale">
            <img src="https://copilot.microsoft.com/th/id/BCO.1853a237-4c9c-4232-84b3-e8ae2bb8df46.png"
                 alt="F1 Logo"
                 class="w-28 md:w-40 mx-auto mb-8 drop-shadow-[0_0_30px_rgba(225,6,0,0.6)]">

            <h1 class="audiowide-regular font-bold uppercase text-4xl md:text-6xl lg:text-7xl mb-6">
                Welcome to <span class="accent">F1&nbsp;Hub</span>
            </h1>

            <p class="text-base md:text-xl text-gray-300 max-w-2xl mx-auto mb-8">
                Race schedules, live standings, driver profiles, team stats, forums, and more —
                your single pit-lane for everything Formula&nbsp;1.
            </p>

            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('races.index') }}" class="btn-f1">View Races</a>
                <a href="{{ route('drivers.index') }}"
                   class="inline-flex items-center gap-2 px-7 py-3 rounded-lg font-bold uppercase tracking-wider border border-white/30 hover:border-white/70 hover:bg-white/5 transition">
                    View Drivers
                </a>
            </div>

            {{-- scroll hint --}}
            <div class="mt-12 flex justify-center">
                <div class="animate-bounce text-white/60">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </div>
            </div>
        </div>
    </section>

    {{-- ─────────────────────── FEATURED DRIVERS ─────────────── --}}
    <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <h2 class="section-title audiowide-regular text-3xl md:text-4xl font-bold uppercase mb-12 reveal">
                Featured Drivers
            </h2>

            <div class="reveal-children grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($drivers->take(3) as $driver)
                    @php
                        $constructorName = $driver->latestStanding->constructor->name ?? 'Unknown';
                        $bgColor  = config('f1.team_colors.' . $constructorName, config('f1.default_team_color'));
                        $flagCode = config('f1.nationality_flags.' . $driver->nationality, config('f1.default_flag_code'));
                        $flagUrl  = "https://flagcdn.com/w40/" . $flagCode . ".png";
                    @endphp

                    <a href="{{ route('drivers.show', $driver) }}" class="block">
                        <div class="f1-card group relative rounded-2xl overflow-hidden"
                             style="background-color: {{ $bgColor }}; height: 740px;">

                            <div class="overflow-hidden rounded-t-2xl">
                                <img
                                    src="https://media.formula1.com/image/upload/f_webp,c_limit,q_50,w_640/content/dam/fom-website/drivers/2025Drivers/{{ $driver->family_name }}"
                                    alt="{{ $driver->given_name }} {{ $driver->family_name }}"
                                    class="w-full h-[520px] object-cover bg-white rounded-t-2xl transform transition-transform duration-700 group-hover:scale-110"
                                    onerror="this.onerror=null;this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/480px-No_image_available.svg.png';"
                                >
                            </div>

                            <div class="p-6 text-white flex flex-col h-[220px] rounded-b-2xl">
                                <div class="flex items-center justify-between mb-3">
                                    <h5 class="text-xl font-bold leading-tight audiowide-regular">
                                        {{ $driver->given_name }} {{ $driver->family_name }}
                                    </h5>
                                    <img src="{{ $flagUrl }}" alt="{{ $driver->nationality }}"
                                         class="w-8 h-5 rounded shadow">
                                </div>

                                <div class="flex-1 flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold mb-2 audiowide-regular text-white">Season Stats</h3>
                                        <ul class="space-y-1 text-base text-white audiowide-regular text-left">
                                            <li><strong>Position:</strong> {{ $driver->latestStanding->position ?? '—' }}</li>
                                            <li><strong>Points:</strong> {{ $driver->latestStanding->points ?? '—' }}</li>
                                            <li><strong>Wins:</strong> {{ $driver->latestStanding->wins ?? '—' }}</li>
                                        </ul>
                                    </div>
                                    <div class="text-5xl font-bold audiowide-regular opacity-80">
                                        #{{ $driver->permanent_number ?? '—' }}
                                    </div>
                                </div>

                                <div class="flex items-center justify-between text-lg audiowide-regular mt-4">
                                    <p></p>
                                    <p><strong>Team:</strong> {{ $constructorName }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="text-center mt-12 reveal">
                <a href="{{ route('drivers.index') }}" class="btn-f1">View All Drivers</a>
            </div>
        </div>
    </section>

    {{-- ─────────────────────── FEATURED RACES ────────────────── --}}
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-gray-900 via-[#0f0f15] to-gray-900">
        <div class="max-w-6xl mx-auto">
            <h2 class="section-title audiowide-regular text-3xl md:text-4xl font-bold uppercase mb-12 reveal">
                Featured Races
            </h2>

            <div class="reveal-children grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($featuredRaces as $race)
                    <div>
                        @if($race['label'])
                            <p class="text-xl font-semibold text-white mb-2 audiowide-regular">
                                {{ $race['label'] }}
                            </p>
                        @endif

                        <div class="race-card f1-card group bg-gray-900 rounded-xl overflow-hidden cursor-pointer border border-white/10"
                             data-name="{{ $race['name'] }}"
                             data-date="{{ $race['date'] }}"
                             data-location="{{ $race['location'] }}"
                             data-status="{{ $race['status'] }}"
                             data-img="{{ $race['img'] }}"
                             data-length="{{ $race['length'] }}"
                             data-turns="{{ $race['turns'] }}"
                             data-lap-record="{{ $race['lapRecord'] }}"
                             data-description="{{ $race['description'] }}"
                             data-results-url="{{ $race['resultsUrl'] }}">

                            <div class="overflow-hidden relative">
                                <img src="{{ $race['img'] }}" alt="{{ $race['name'] }}"
                                     class="w-full h-48 object-cover transform transition-transform duration-500 group-hover:scale-110">
                                <span class="absolute top-3 right-3 px-2 py-1 text-xs font-bold rounded text-white {{ $race['statusClass'] }}">
                                    {{ $race['status'] }}
                                </span>
                            </div>

                            <div class="p-4">
                                <p class="text-sm text-gray-400">{{ $race['date'] }}</p>
                                <h4 class="text-xl font-bold mt-1 audiowide-regular">{{ $race['name'] }}</h4>
                                <p class="text-sm text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300 mt-1">
                                    {{ $race['tagline'] ?? 'Press to see more info' }}
                                </p>
                                <p class="text-gray-400 mt-1">{{ $race['location'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-12 reveal">
                <a href="{{ route('races.index') }}" class="btn-f1">View All Races</a>
            </div>
        </div>
    </section>

    {{-- Modal (kept exactly) --}}
    <div id="raceModalOverlay" class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50">
        <div id="raceModal" class="bg-gray-800 text-white rounded-lg w-full max-w-2xl p-5 relative">
            <button class="absolute top-2 right-3 text-2xl" id="closeModal">&times;</button>
            <h3 id="modalTitle" class="text-xl font-bold mb-3"></h3>
            <img id="modalImage" src="" alt="Track layout" class="rounded mb-4">
            <div id="modalInfo" class="space-y-1"></div>
            <div id="modalActions" class="mt-4 flex justify-end gap-2"></div>
        </div>
    </div>

    {{-- ─────────────────────── COUNTDOWN ─────────────────────── --}}
    <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="reveal-scale bg-gradient-to-br from-[#15151e] via-[#1a1a28] to-[#15151e] border border-white/10 rounded-2xl p-8 sm:p-10 max-w-5xl mx-auto text-center relative overflow-hidden">
            <div class="absolute top-0 left-0 h-1 w-full bg-gradient-to-r from-[#e10600] via-[#e10600] to-transparent"></div>

            <h2 class="section-title audiowide-regular text-2xl sm:text-3xl font-bold uppercase mb-4 inline-block">
                Next Race Countdown
            </h2>
            <p class="mb-10 text-gray-300 text-lg">
                {{ $nextRace['raceName'] ?? 'No upcoming race' }}
                @if(!empty($nextRace))
                    — {{ $nextRace['Circuit']['Location']['locality'] ?? '' }},
                    {{ $nextRace['Circuit']['Location']['country'] ?? '' }}
                @endif
            </p>

            @if($nextRace)
                <div class="flex flex-col sm:flex-row justify-center items-center gap-6 sm:gap-10">
                    @foreach([
                        'days-next'    => 'Days',
                        'hours-next'   => 'Hours',
                        'minutes-next' => 'Minutes',
                        'seconds-next' => 'Seconds',
                    ] as $id => $label)
                        <div class="flex flex-col items-center">
                            <span id="{{ $id }}"
                                  class="countdown-box audiowide-regular text-4xl sm:text-6xl font-extrabold text-white
                                         bg-black/30 backdrop-blur-sm
                                         w-24 h-24 sm:w-36 sm:h-36 flex items-center justify-center
                                         border-2 border-white rounded-lg">
                                0
                            </span>
                            <span class="text-xs sm:text-sm uppercase tracking-widest text-gray-400 mt-3">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-3xl font-bold text-red-500">No upcoming race</div>
            @endif
        </div>
    </section>

    {{-- ─────────────────────── COMMUNITY ─────────────────────── --}}
    <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="reveal-children grid grid-cols-1 md:grid-cols-2 gap-8 max-w-6xl mx-auto">
            <div class="f1-card bg-gradient-to-br from-[#1a1a28] to-[#0f0f15] border border-white/10 rounded-2xl p-8 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-[#e10600]"></div>
                <h2 class="audiowide-regular text-2xl font-bold uppercase mb-4">Forums</h2>
                <p class="mb-6 text-gray-300">
                    Join the conversation with other F1 fans. Share your thoughts, discuss races, and connect with the community.
                </p>
                <a href="{{ route('forums.index') }}" class="btn-f1">Go to Forums</a>
            </div>

            <div class="f1-card bg-gradient-to-br from-[#1a1a28] to-[#0f0f15] border border-white/10 rounded-2xl p-8 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-[#e10600]"></div>
                <h2 class="audiowide-regular text-2xl font-bold uppercase mb-4">Make Your Predictions</h2>
                <p class="mb-6 text-gray-300">
                    Think you know who will win the next race? Submit your predictions and see how you stack up against others.
                </p>
                <a href="{{ route('game.index') }}" class="btn-f1">Predict Now</a>
            </div>
        </div>
    </section>

</div>

{{-- Countdown + Modal scripts (logic preserved) --}}
<script>
(function(){
    @if($nextRace)
    const raceDate = new Date("{{ $nextRace['date'] }}T{{ $nextRace['time'] ?? '00:00:00Z' }}").getTime();

    const daysEl    = document.getElementById("days-next");
    const hoursEl   = document.getElementById("hours-next");
    const minutesEl = document.getElementById("minutes-next");
    const secondsEl = document.getElementById("seconds-next");

    function updateCountdown() {
        const now = new Date().getTime();
        const distance = raceDate - now;

        if (distance <= 0) {
            daysEl.innerHTML = "0";
            hoursEl.innerHTML = "0";
            minutesEl.innerHTML = "0";
            secondsEl.innerHTML = "0";
            clearInterval(timer);
            return;
        }

        daysEl.innerHTML    = Math.floor(distance / (1000 * 60 * 60 * 24));
        hoursEl.innerHTML   = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        minutesEl.innerHTML = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        secondsEl.innerHTML = Math.floor((distance % (1000 * 60)) / 1000);
    }

    updateCountdown();
    const timer = setInterval(updateCountdown, 1000);
    @endif
})();

// Race modal
(function(){
    const modalOverlay = document.getElementById('raceModalOverlay');
    const closeModalBtn = document.getElementById('closeModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalImage = document.getElementById('modalImage');
    const modalInfo = document.getElementById('modalInfo');
    const modalActions = document.getElementById('modalActions');

    function openModal(data) {
        modalTitle.textContent = data.name;
        modalImage.src = data.img;
        modalImage.alt = `${data.name} track layout`;

        modalInfo.innerHTML = `
            <p><strong>Date:</strong> ${data.date}</p>
            <p><strong>Location:</strong> ${data.location}</p>
            <p><strong>Status:</strong> ${data.status}</p>
            ${data.length ? `<p><strong>Track Length:</strong> ${data.length}</p>` : ''}
            ${data.turns ? `<p><strong>Turns:</strong> ${data.turns}</p>` : ''}
            ${data.lapRecord ? `<p><strong>Lap Record:</strong> ${data.lapRecord}</p>` : ''}
            ${data.description ? `<p><strong>About:</strong> ${data.description}</p>` : ''}
        `;

        modalActions.innerHTML = '';
        if (data.status === 'Completed') {
            const resultsBtn = document.createElement('a');
            resultsBtn.href = data.resultsUrl;
            resultsBtn.textContent = 'View Results';
            resultsBtn.className = 'px-4 py-2 bg-green-600 hover:bg-green-700 rounded font-bold';
            modalActions.appendChild(resultsBtn);
        }
        const closeBtn = document.createElement('button');
        closeBtn.textContent = 'Close';
        closeBtn.className = 'px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded font-bold';
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
    modalOverlay.addEventListener('click', (e) => {
        if (e.target === modalOverlay) closeModal();
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal();
    });

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
})();

// Scroll-reveal observer
(function(){
    const targets = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale, .reveal-children');
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
    }, { threshold: 0.15, rootMargin: '0px 0px -60px 0px' });

    targets.forEach(el => io.observe(el));
})();
</script>
</x-app-layout>
