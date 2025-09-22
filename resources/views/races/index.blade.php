<x-app-layout>
<div class="max-w-7xl mx-auto px-4 py-8 text-white">
    <h2 class="text-3xl font-bold text-center mb-8 audiowide-regular">Featured Races</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($featuredRaces as $race)
            <div class="race-card bg-gray-900 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition cursor-pointer"
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
                <div class="p-4 border-b border-gray-700 text-center font-semibold">{{ $race['label'] }}</div>
                <img src="{{ $race['img'] }}" alt="{{ $race['name'] }}" class="w-full h-48 object-cover">
                <div class="p-4">
                    <p class="text-sm text-gray-400">{{ $race['date'] }}</p>
                    <h4 class="text-xl font-bold mt-1">{{ $race['name'] }}</h4>
                    <p class="text-gray-400">{{ $race['location'] }}</p>
                    <span class="inline-block mt-2 px-2 py-1 text-xs font-bold rounded text-white {{ $race['statusClass'] }}">{{ $race['status'] }}</span>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8 text-white">
    <h2 class="text-2xl font-bold mb-6 audiowide-regular">All Races</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach($allRaces as $race)
            <div class="race-card bg-gray-900 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition cursor-pointer"
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
                <img src="{{ $race['img'] }}" alt="{{ $race['name'] }}" class="w-full h-40 object-cover">
                <div class="p-4">
                    <p class="text-sm text-gray-400">{{ $race['date'] }}</p>
                    <h4 class="text-lg font-bold mt-1">{{ $race['name'] }}</h4>
                    <p class="text-gray-400">{{ $race['location'] }}</p>
                    <span class="inline-block mt-2 px-2 py-1 text-xs font-bold rounded text-white {{ $race['statusClass'] }}">{{ $race['status'] }}</span>
                </div>
            </div>
        @endforeach
    </div>
</div>

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
