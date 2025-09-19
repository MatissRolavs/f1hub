<x-app-layout>
<style>
    .carousel-wrapper { position: relative; overflow: hidden; max-width: 1000px; margin: auto; padding: 0 50px; }
    .carousel-track { display: flex; transition: transform 0.4s ease; }
    .event-card { background-color: #1a1a1a; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.4); color: #fff; font-family: monospace; margin: 0 0.75rem; flex: 0 0 calc(33.333% - 1.5rem); display: flex; flex-direction: column; cursor: pointer; }
    .event-card img { width: 100%; height: 160px; object-fit: cover; }
    .event-body { padding: 1rem; }
    .event-date { font-size: 0.9rem; color: #ccc; }
    .event-title { font-size: 1.1rem; font-weight: bold; margin: 0.5rem 0; }
    .event-location { font-size: 0.95rem; color: #aaa; }
    .arrow { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.5); color: white; border: none; padding: 0.5rem 0.8rem; cursor: pointer; font-size: 1.5rem; border-radius: 50%; z-index: 2; }
    .arrow.left { left: 10px; }
    .arrow.right { right: 10px; }
    .arrow:disabled { opacity: 0.3; cursor: default; }
    .page-center { min-height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 2rem 0; }

    /* Modal */
    #raceModalOverlay { position: fixed; inset: 0; background: rgba(0,0,0,0.7); display: none; align-items: center; justify-content: center; z-index: 50; }
    #raceModalOverlay.active { display: flex; }
    #raceModal { background: rgb(31 41 55); color: #fff; border-radius: 12px; width: 100%; max-width: 640px; padding: 1.25rem; position: relative; }
    #raceModal .close { position: absolute; top: 8px; right: 12px; font-size: 1.75rem; color: #fff; background: transparent; border: 0; cursor: pointer; }
    #modalInfo p { font-size: 0.95rem; color: #d1d5db; }
    #modalActions { display: flex; gap: 0.5rem; justify-content: flex-end; margin-top: 0.75rem; }
    .btn { padding: 0.45rem 0.8rem; border-radius: 8px; font-weight: 700; font-family: monospace; border: 0; cursor: pointer; }
    .btn-green { background: #16a34a; color: #fff; }
    .btn-green:hover { background: #15803d; }
    .btn-gray { background: #374151; color: #fff; }
    .btn-gray:hover { background: #4b5563; }

    
</style>

@php
    $f1Images = [
        'https://running-riversport.com/wp-content/uploads/2022/09/4-Best-F1-tracks.jpg',
        'https://www.cmcmotorsports.com/cdn/shop/articles/f1-cars-corner_5a8d606f-ff31-4542-917c-c4791f88ec49_1024x.jpg?v=1741191660',
        'https://t3.ftcdn.net/jpg/13/22/58/86/360_F_1322588670_REIoCPfaSiVcN7ZibFuYeZIfdVQVBEZL.jpg',
        'https://www.topgear.com/sites/default/files/news-listicle/image/2022/06/0-Best-F1-tracks.jpg',
        'https://static.independent.co.uk/s3fs-public/thumbnails/image/2018/05/18/12/formula-1.jpg?width=1200&height=630&fit=crop',
        'https://cdn.racingnews365.com/_1800x945_crop_center-center_75_none/E_BeHUFX0AA0QLs.jpeg?v=1673948090',
    ];
@endphp

<div class="page-center">
@if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">{{ session('success') }}</div>
    @endif
    <h2 class="text-2xl font-bold text-center text-white audiowide-regular">CURRENT SEASON RACES</h2>
    <div class="carousel-wrapper">
        <button class="arrow left" id="prevBtn">&#10094;</button>
        <div class="carousel-track" id="carousel-track">
        @foreach($races as $race)
            @php
                $randomImage = $f1Images[array_rand($f1Images)];
                $raceDate = \Carbon\Carbon::parse($race->date);
                $status = $raceDate->isPast() ? 'Completed' : 'Upcoming';
                $statusClass = $raceDate->isPast() ? 'bg-green-600' : 'bg-yellow-500';
                $trackImage = $race->track_image ?: $randomImage;
            @endphp
            <div class="event-card race-card"
                 data-name="{{ $race->name }}"
                 data-date="{{ $raceDate->format('D, d M Y') }}"
                 data-location="{{ $race->locality }}, {{ $race->country }}"
                 data-status="{{ $status }}"
                 data-img="{{ $trackImage }}"
                 data-length="{{ $race->track_length }}"
                 data-turns="{{ $race->turns }}"
                 data-lap-record="{{ $race->lap_record }}"
                 data-description="{{ $race->description }}"
                 data-results-url="{{ route('races.show', ['season' => $race->season, 'round' => $race->round]) }}">
                <img src="{{ $randomImage }}" alt="{{ $race->name }}">
                <div class="event-body">
                    <div class="flex items-center gap-2 event-date">
                        <span class="audiowide-regular">{{ $raceDate->format('D, d M Y') }}</span>
                        <span class="inline-block px-2 py-1 text-xs font-bold rounded text-white {{ $statusClass }} audiowide-regular">
                            {{ $status }}
                        </span>
                    </div>
                    <div class="event-title audiowide-regular">{{ $race->name }}</div>
                    <div class="event-location audiowide-regular">{{ $race->locality }}, {{ $race->country }}</div>
                </div>
            </div>
        @endforeach
        </div>
        <button class="arrow right" id="nextBtn">&#10095;</button>
    </div>
</div>

<!-- Modal -->
<div id="raceModalOverlay">
    <div id="raceModal">
        <button class="close" id="closeModal">&times;</button>
        <h3 id="modalTitle"></h3>
        <img id="modalImage" src="" alt="Track layout">
        <div id="modalInfo"></div>
        <div id="modalActions"></div>
    </div>
</div>

<script>
    const track = document.getElementById('carousel-track');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const cards = track.querySelectorAll('.event-card');
    const totalCards = cards.length;
    const cardsPerView = 3;

    let currentIndex = {{ $startIndex }};
    currentIndex = Math.floor(currentIndex / cardsPerView) * cardsPerView;

    function updateCarousel() {
        const cardWidth = cards[0].offsetWidth + 24;
        track.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
        prevBtn.disabled = currentIndex === 0;
        nextBtn.disabled = currentIndex >= totalCards - cardsPerView;
    }
    prevBtn.addEventListener('click', () => { currentIndex -= cardsPerView; updateCarousel(); });
    nextBtn.addEventListener('click', () => { currentIndex += cardsPerView; updateCarousel(); });
    window.addEventListener('resize', updateCarousel);
    updateCarousel();

    // Modal logic
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
            resultsBtn.className = 'btn btn-green';
            modalActions.appendChild(resultsBtn);
        }
        const closeBtn = document.createElement('button');
        closeBtn.textContent = 'Close';
        closeBtn.className = 'btn btn-gray';
        closeBtn.addEventListener('click', closeModal);
        modalActions.appendChild(closeBtn);

        modalOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modalOverlay.classList.remove('active');
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
</script>
</x-app-layout>
