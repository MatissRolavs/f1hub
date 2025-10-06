<x-app-layout>
    <!-- Featured Races Section -->
    <section class="w-full min-h-screen bg-gray-950 text-white flex flex-col relative overflow-hidden">
    <!-- Racing flag accents in corners -->
    <img src="{{ asset('images/raceline.jpg') }}" 
         alt="Racing flag line" 
         class="absolute top-0 left-0 w-32 transform -rotate-45 opacity-80 pointer-events-none select-none">

    <img src="{{ asset('images/raceline.jpg') }}" 
         alt="Racing flag line" 
         class="absolute top-0 right-0 w-32 transform rotate-45 -scale-x-100 opacity-80 pointer-events-none select-none">

    <div class="max-w-7xl mx-auto px-4 pt-20 pb-16 flex flex-col flex-1">
       <!-- Animated Track -->
        <div id="animated-track" class="hidden md:flex justify-center items-center py-8 perspective-1000 overflow-visible">
            <div id="trackWrapper" 
                class="transform-style-preserve-3d" 
                style="transform: rotateX(60deg) scale(2.5); transform-origin: center;">
                <svg viewBox="0 0 600 400" 
                    width="600" height="200"  
                    class="relative" 
                    id="trackSvg">
                    <path d="m490.05 299.77c16.808-24.592 0.66497-57.836-34.045-51.106-11.377 2.206-14.444 8.6956-10.241 21.672 4.399 13.579 4.8848 13.383-29.629 11.999-15.386-0.61702-36.964-1.3708-47.95-1.6751-27.435-0.75994-87.181-3.7741-91.736-4.6278-4.338-0.81306-4.9747-1.9562-10.912-19.566-6.0748-18.017-3.1485-20.098 24.324-17.289 44.704 4.5716 57.606-20.625 31.837-62.181-17.295-27.89-17.268-47.174 0.0895-64.49 13.139-13.107 19.257-14.122 66.864-11.084 42.15 2.6897 56.859 1.5632 70.668-5.4123 21.398-10.809 15.882-15.451-33.644-28.321-44.524-11.57-42.802-11.381-67.703-7.4009-10.66 1.704-25.959 3.6865-33.999 4.4068-8.0398 0.72029-21.057 2.3314-28.926 3.5785-26.213 4.1539-34.42 1.8511-42.539-11.933-10.271-17.439 2.7058-28.334 16.173-13.577 11.201 12.273 18.447 12.455 26.19 0.66263 12.229-18.623-9.9568-43.345-34.793-38.772-16.038 2.9532-25.482 13.222-33.781 36.736-2.7239 7.7175-5.6328 15.738-6.464 17.824-0.83113 2.0862-14.984 41.244-31.451 87.017-50.781 141.15-51.54 143.21-53.474 145-2.8829 2.6772-2.1578 2.6053-55.675 5.4587-57.364 3.0585-61.411 3.8371-72.32 13.905-1.2497 1.1533-2.4682 2.3098-2.707 2.5708-0.59522 0.65072 1.4105 5.0164 3.2039 6.9734 1.9529 2.1311-1.4502 2.062 59.441 1.2134 48.318-0.67342 86.183-0.70049 132.01-0.0929 16.254 0.21548 32.574 0.22163 125.88 0.0461 146.88-0.27627 137.29 0.2191 150.45-7.7817 6.4561-3.9238 11.364-8.6537 14.852-13.758z"
                        fill="none" stroke="white" stroke-width="4" id="shanghaiPath" stroke-linecap="round"/>
                    <!-- Car -->
                    <rect class="car" x="0" y="0" width="12" height="5" fill="red" rx="4" ry="4" />
                </svg>
            </div>
        </div>


        <!-- Heading flush with cards -->
        <h2 id="featuredHeading" class="text-5xl font-bold text-left mt-auto mb-6 audiowide-regular">
            Current F1 Season Featured Races
        </h2>


        <!-- Grid directly under heading -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($featuredRaces as $race)
                <div>
                    <!-- Label above card, aligned left -->
                    @if($race['label'])
                        <p class="text-2xl font-semibold text-white mb-2 audiowide-regular">
                            {{ $race['label'] }}
                        </p>
                    @endif

                    <!-- Card itself -->
                    <div class="race-card group bg-gray-900 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition cursor-pointer"
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

                        <!-- Image with zoom effect -->
                        <div class="overflow-hidden">
                            <img src="{{ $race['img'] }}" alt="{{ $race['name'] }}"
                                class="w-full h-48 object-cover transform transition-transform duration-500 group-hover:scale-110">
                        </div>

                        <div class="p-4">
                            <p class="text-sm text-gray-400">{{ $race['date'] }}</p>
                            <h4 class="text-xl font-bold mt-1">{{ $race['name'] }}</h4>

                            <!-- Tagline appears only on hover -->
                            <p class="text-sm text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                {{ $race['tagline'] ?? 'Press to see more info' }}
                            </p>

                            <p class="text-gray-400">{{ $race['location'] }}</p>
                            <span class="inline-block mt-2 px-2 py-1 text-xs font-bold rounded text-white {{ $race['statusClass'] }}">
                                {{ $race['status'] }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>
    <!-- All Races Section -->
    <section class="w-full bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto px-4 py-16">
            <h2 class="text-2xl font-bold mb-6 audiowide-regular">ALL RACES</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($allRaces as $race)
                <div class="race-card group bg-gray-900 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition cursor-pointer"
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

                    

                    <!-- Image with zoom effect -->
                    <div class="overflow-hidden">
                        <img src="{{ $race['img'] }}" alt="{{ $race['name'] }}"
                            class="w-full h-48 object-cover transform transition-transform duration-500 group-hover:scale-110">
                    </div>

                    <div class="p-4">
                        <p class="text-sm text-gray-400">{{ $race['date'] }}</p>
                        <h4 class="text-xl font-bold mt-1">{{ $race['name'] }}</h4>

                        <!-- Tagline appears only on hover -->
                        <p class="text-sm text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            {{ $race['tagline'] ?? 'Press to see more info' }}
                        </p>

                        <p class="text-gray-400">{{ $race['location'] }}</p>
                        <span class="inline-block mt-2 px-2 py-1 text-xs font-bold rounded text-white {{ $race['statusClass'] }}">
                            {{ $race['status'] }}
                        </span>
                    </div>
                    @if(!empty($race['top3']))
                        <div class="border-t border-gray-700 bg-gray-900/80 px-4 py-3">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs tracking-wider text-gray-400">Top 3 finishers</span>
                                <a href="{{ $race['resultsUrl'] }}" class="text-xs text-blue-400 hover:text-blue-300">Full results</a>
                            </div>
                            <ul class="space-y-2">
                                @foreach($race['top3'] as $i => $res)
                                    <li class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full 
                                                @if($i===0) bg-yellow-500 text-black 
                                                @elseif($i===1) bg-gray-300 text-black 
                                                @else bg-amber-800 text-white @endif
                                                text-xs font-bold">
                                                {{ $i + 1 }}
                                            </span>
                                            <span class="text-sm font-semibold text-white">{{ $res['driver'] }}</span>
                                            <span class="text-xs text-gray-400">({{ $res['team'] }})</span>
                                        </div>
                                        <span class="text-sm tabular-nums text-gray-300">{{ $res['time'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="border-t border-gray-700 bg-gray-900/80 px-4 py-3 text-center">
                            <span class="text-sm text-gray-400">No race results yet</span>
                        </div>
                    @endif

                </div>

                @endforeach
            </div>
        </div>
    </section>

<!-- Modal -->
<div id="raceModalOverlay" class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50">
    <div id="raceModal" class="bg-gray-800 text-white rounded-lg w-full max-w-3xl p-5 relative">
        <button class="absolute top-2 right-3 text-2xl" id="closeModal">&times;</button>
        <h3 id="modalTitle" class="text-xl font-bold mb-3"></h3>
        
        <!-- Track container -->
        <div id="modalTrack" class="w-full flex justify-center mb-4"></div>
        
        <div id="modalInfo" class="space-y-1"></div>
        <div id="modalActions" class="mt-4 flex justify-end gap-2"></div>
    </div>
</div>


<script>
    const modalOverlay = document.getElementById('raceModalOverlay');
    const closeModalBtn = document.getElementById('closeModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalTrack = document.getElementById('modalTrack');
    const modalInfo = document.getElementById('modalInfo');
    const modalActions = document.getElementById('modalActions');

    function openModal(data) {
    modalTitle.textContent = data.name;

    // Show fallback preview image
    

    // Clear old SVG
    modalTrack.innerHTML = '';

    // Try load SVG
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

            // Make SVG responsive
            const svgElement = modalTrack.querySelector('svg');
            if (svgElement) {
                svgElement.removeAttribute('width');
                svgElement.removeAttribute('height');
                svgElement.setAttribute('class', 'w-full h-auto');
            }

            const path = svgElement?.querySelector('path');
            if (path) {
                const pathLength = path.getTotalLength();

                // Base faint gray stroke
                path.setAttribute('stroke', 'white');
                path.setAttribute('stroke-opacity', '0.3');
                path.setAttribute('stroke-width', '3');
                path.setAttribute('fill', 'none');

                // Bright animated stroke overlay
                const brightPath = path.cloneNode();
                brightPath.setAttribute('stroke', 'white');
                brightPath.setAttribute('stroke-opacity', '1');
                brightPath.setAttribute('stroke-width', '3');
                brightPath.setAttribute('stroke-dasharray', pathLength);
                brightPath.setAttribute('stroke-dashoffset', pathLength);
                brightPath.setAttribute('fill', 'none');
                path.parentNode.appendChild(brightPath);

                // Car element
                const car2 = document.createElementNS("http://www.w3.org/2000/svg", "rect");
                car2.setAttribute('width', '14');
                car2.setAttribute('height', '6');
                car2.setAttribute('fill', 'red');
                car2.setAttribute('rx', '3');
                car2.setAttribute('ry', '3');
                car2.classList.add('car2');
                path.parentNode.appendChild(car2);

                // Animate car + overlay stroke reveal
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
                        const dashOffset = pathLength - (progress * pathLength);
                        brightPath.setAttribute('stroke-dashoffset', dashOffset);
                    }
                });

                // Spin wrapper (3D hologram effect)
                anime({
                    targets: '#modalTrackWrapper',
                    rotateZ: '-360deg',
                    duration: 25000,
                    easing: 'linear',
                    loop: true
                });
            }
        })
        .catch(() => {
            modalImage.classList.remove('hidden');
        });

    // Info
    modalInfo.innerHTML = `
        <p><strong>Date:</strong> ${data.date}</p>
        <p><strong>Location:</strong> ${data.location}</p>
        <p><strong>Status:</strong> ${data.status}</p>
        ${data.length ? `<p><strong>Track Length:</strong> ${data.length}</p>` : ''}
        ${data.turns ? `<p><strong>Turns:</strong> ${data.turns}</p>` : ''}
        ${data.lapRecord ? `<p><strong>Lap Record:</strong> ${data.lapRecord}</p>` : ''}
        ${data.description ? `<p><strong>About:</strong> ${data.description}</p>` : ''}
    `;

    // Buttons
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
    modalOverlay.addEventListener('click', (e) => {
        if (e.target === modalOverlay) closeModal();
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal();
    });

    // Attach modal open to all race cards
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


<!-- Anime.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
<script>
    // Scroll-triggered animation helper
    function animateOnScroll(targets, animationOptions) {
        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    anime({
                        targets: entry.target,
                        ...animationOptions
                    });
                    obs.unobserve(entry.target); // run once
                }
            });
        }, { threshold: 0.2 });

        if (NodeList.prototype.isPrototypeOf(targets) || Array.isArray(targets)) {
            targets.forEach(el => observer.observe(el));
        } else if (targets) {
            observer.observe(targets);
        }
    }

    // Headings
    animateOnScroll(document.querySelector('h2.text-5xl'), {
        opacity: [0, 1],
        translateY: [50, 0],
        duration: 800,
        easing: 'easeOutExpo'
    });

    animateOnScroll(document.querySelector('section.bg-gray-800 h2'), {
        opacity: [0, 1],
        translateY: [50, 0],
        duration: 800,
        easing: 'easeOutExpo'
    });

    // Featured race cards
    animateOnScroll(document.querySelectorAll('section.bg-gray-950 .race-card'), {
        opacity: [0, 1],
        translateY: [40, 0],
        scale: [0.95, 1],
        duration: 1000,
        easing: 'easeOutElastic(1, .8)',
        delay: anime.stagger(100)
    });

    // All race cards
    animateOnScroll(document.querySelectorAll('section.bg-gray-800 .race-card'), {
        opacity: [0, 1],
        translateY: [40, 0],
        scale: [0.95, 1],
        duration: 1000,
        easing: 'easeOutElastic(1, .8)',
        delay: anime.stagger(100)
    });
</script>
<!-- Anime.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <script>
        const path = document.querySelector('#shanghaiPath');
        const pathLength = path.getTotalLength();

        // Keep faint base track always visible
        path.setAttribute('stroke', 'white');
        path.setAttribute('stroke-opacity', '0.3');
        path.setAttribute('stroke-width', '3');

        // Overlay bright path
        const brightPath = path.cloneNode();
        brightPath.setAttribute('stroke', 'white');
        brightPath.setAttribute('stroke-opacity', '1');
        brightPath.setAttribute('stroke-width', '3');
        brightPath.setAttribute('stroke-dasharray', pathLength);
        brightPath.setAttribute('stroke-dashoffset', pathLength);
        path.parentNode.appendChild(brightPath);

        // Car follows path
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
                const dashOffset = pathLength - (progress * pathLength);
                brightPath.setAttribute('stroke-dashoffset', dashOffset);
            }
        });

        // Keep X at 60°, spin only Y
        anime({
            targets: '#trackWrapper',
            rotateZ: '-360deg',
            duration: 25000,
            easing: 'linear',
            loop: true
        });
    </script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const h2 = document.querySelector('#featuredHeading');
    const text = h2.textContent.trim();
    const words = text.split(' ');

    // Replace heading text with spans for each word
    h2.innerHTML = words
        .map(word => `<span class="inline-block opacity-0 translate-y-10">${word}&nbsp;</span>`)
        .join('');

    const wordSpans = h2.querySelectorAll('span');

    // Animate each word coming up
    anime({
        targets: wordSpans,
        translateY: ['100%', '0%'],
        opacity: [0, 1],
        duration: 800,
        easing: 'easeOutExpo',
        delay: anime.stagger(150)
    });
});

</script>

</x-app-layout>
