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
        
        <!-- Heading flush with cards -->
        <h2 class="text-5xl font-bold text-left mt-auto mb-6 audiowide-regular">
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
    <div id="raceModal" class="bg-gray-800 text-white rounded-lg w-full max-w-2xl p-5 relative">
        <button class="absolute top-2 right-3 text-2xl" id="closeModal">&times;</button>
        <h3 id="modalTitle" class="text-xl font-bold mb-3"></h3>
        <img id="modalImage" src="" alt="Track layout" class="rounded mb-4">
        <div id="modalInfo" class="space-y-1"></div>
        <div id="modalActions" class="mt-4 flex justify-end gap-2"></div>
    </div>
</div>

<script>
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
</x-app-layout>
