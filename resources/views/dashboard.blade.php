<x-app-layout>
<div class="min-h-screen flex flex-col gap-12 px-4 sm:px-6 lg:px-8 py-12 bg-gray-900 font-mono text-white tracking-[1.5px] leading-[1.8]">
    <!-- Welcome Box -->
    <div class="bg-white/5 border border-white/20 rounded-xl p-6 sm:p-8 max-w-5xl w-full mx-auto text-center shadow-[0_0_20px_rgba(255,0,0,0.3)] hover:-translate-y-[5px] hover:shadow-[0_0_30px_rgba(255,0,0,0.6)] transition-all duration-300">
        <img src="https://copilot.microsoft.com/th/id/BCO.1853a237-4c9c-4232-84b3-e8ae2bb8df46.png"
             alt="F1 Logo"
             class="max-w-[120px] md:max-w-[150px] h-auto mx-auto mb-4 block">
        <h1 class="audiowide-regular font-bold uppercase mb-4 text-xl md:text-3xl">Welcome to F1 Hub</h1>
        <p class="text-base md:text-lg">
            Your ultimate destination for everything Formula&nbsp;1 — race schedules, live standings, driver profiles, team stats, forums, and more.
        </p>
    </div>
   <!-- Drivers Preview -->
<div class="max-w-7xl mx-auto w-full px-4">
    <h2 class="audiowide-regular text-2xl font-bold uppercase mb-10 text-center">Featured Drivers</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach($drivers->take(3) as $driver)
            @php
                $constructorName = $driver->latestStanding->constructor->name ?? 'Unknown';
                $teamColors = [
                    'Red Bull' => '#4781D7', 'Ferrari' => '#ED1131', 'Mercedes' => '#00D7B6',
                    'McLaren' => '#F47600', 'Alpine F1 Team' => '#00A1E8', 'Aston Martin' => '#229971',
                    'Williams' => '#1868DB', 'RB F1 Team' => '#6C98FF', 'Sauber' => '#01C00E',
                    'Haas F1 Team' => '#9C9FA2',
                ];
                $bgColor = $teamColors[$constructorName] ?? '#E5E7EB';
                $nationalityMap = [
                    'Australian' => 'au','Austrian' => 'at','Belgian' => 'be','Brazilian' => 'br',
                    'British' => 'gb','Canadian' => 'ca','Chinese' => 'cn','Danish' => 'dk',
                    'Dutch' => 'nl','Finnish' => 'fi','French' => 'fr','German' => 'de',
                    'Italian' => 'it','Japanese' => 'jp','Mexican' => 'mx','Monegasque' => 'mc',
                    'New Zealander' => 'nz','Polish' => 'pl','Portuguese' => 'pt','Russian' => 'ru',
                    'Spanish' => 'es','Swedish' => 'se','Swiss' => 'ch','Thai' => 'th',
                    'Turkish' => 'tr','American' => 'us','Czech' => 'cz','South African' => 'za',
                    'Argentine' => 'ar','Indian' => 'in','Irish' => 'ie','Ukrainian' => 'ua',
                    'Estonian' => 'ee','Latvian' => 'lv','Lithuanian' => 'lt',
                ];
                $flagCode = $nationalityMap[$driver->nationality] ?? 'xx';
                $flagUrl = "https://flagcdn.com/w40/" . $flagCode . ".png";
            @endphp

            <a href="{{ route('drivers.show', $driver) }}" class="block">
                <div class="group relative rounded-2xl shadow-lg overflow-hidden transition-all duration-500 transform"
                     style="background-color: {{ $bgColor }}; height: 740px;">

                    <!-- Image with zoom hover -->
                    <div class="overflow-hidden rounded-t-2xl">
                        <img 
                            src="https://media.formula1.com/image/upload/f_webp,c_limit,q_50,w_640/content/dam/fom-website/drivers/2025Drivers/{{ $driver->family_name }}" 
                            alt="{{ $driver->given_name }} {{ $driver->family_name }}"
                            class="w-full h-[520px] object-cover bg-white rounded-t-2xl transform transition-transform duration-700 group-hover:scale-110"
                            onerror="this.onerror=null;this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/480px-No_image_available.svg.png';"
                        >
                    </div>

                    <!-- Bottom content -->
                    <div class="p-6 text-white flex flex-col h-[220px] rounded-b-2xl">
                        <!-- Name + Flag -->
                        <div class="flex items-center justify-between mb-3">
                            <h5 class="text-xl font-bold leading-tight audiowide-regular">
                                {{ $driver->given_name }} {{ $driver->family_name }}
                            </h5>
                            <img src="{{ $flagUrl }}" alt="{{ $driver->nationality }}" class="w-8 h-5 rounded shadow">
                        </div>

                        <!-- Season Stats + Number -->
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

                        <!-- Team -->
                        <div class="flex items-center justify-between text-lg audiowide-regular mt-4">
                            <p></p>
                            <p><strong>Team:</strong> {{ $constructorName }}</p>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <!-- CTA button -->
    <div class="text-center mt-10">
        <a href="{{ route('drivers.index') }}" 
           class="inline-block px-6 py-3 bg-red-600 hover:bg-red-700 rounded-lg font-bold uppercase tracking-wide">
            View All Drivers
        </a>
    </div>
</div>

    <!-- Featured Races Preview -->
<div class="max-w-6xl mx-auto w-full">
    <h2 class="audiowide-regular text-2xl font-bold uppercase mb-6 text-center">Featured Races</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($featuredRaces as $race)
            <div>
                <!-- Label above card -->
                @if($race['label'])
                    <p class="text-2xl font-semibold text-white mb-2 audiowide-regular">
                        {{ $race['label'] }}
                    </p>
                @endif

                <!-- Race Card -->
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

    <div class="text-center mt-6">
        <a href="{{ route('races.index') }}" 
           class="inline-block px-6 py-2 bg-red-600 hover:bg-red-700 rounded-lg font-bold uppercase tracking-wide">
            View All Races
        </a>
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
<!-- Next Race Timer -->
<div class="bg-white/5 border border-white/20 rounded-xl p-6 sm:p-8 max-w-5xl mx-auto text-center shadow-lg">
    <h2 class="audiowide-regular text-2xl sm:text-3xl font-bold uppercase mb-6">Next Race Countdown</h2>
    <p class="mb-8 text-gray-300 text-lg">
    {{ $nextRace['raceName'] ?? 'No upcoming race' }} — 
    {{ $nextRace['Circuit']['Location']['locality'] ?? '' }},
    {{ $nextRace['Circuit']['Location']['country'] ?? '' }}
    </p>

    @if($nextRace)
        <div class="w-full mb-6">
            <div class="flex flex-col sm:flex-row justify-center items-center gap-6 sm:gap-12">
                <!-- Days -->
                <div class="flex flex-col items-center">
                    <span id="days-next" 
                          class="text-5xl sm:text-7xl font-extrabold text-white 
                                 bg-transparent backdrop-blur-sm 
                                 w-24 h-24 sm:w-40 sm:h-40 flex items-center justify-center 
                                 border-2 border-white rounded-lg 
                                 shadow-[0_0_10px_rgba(255,255,255,0.8),0_0_25px_rgba(0,0,0,1)]">
                        0
                    </span>
                    <span class="text-sm uppercase text-gray-300 mt-2">Days</span>
                </div>

                <!-- Hours -->
                <div class="flex flex-col items-center">
                    <span id="hours-next" 
                          class="text-5xl sm:text-7xl font-extrabold text-white 
                                 bg-transparent backdrop-blur-sm 
                                 w-24 h-24 sm:w-40 sm:h-40 flex items-center justify-center 
                                 border-2 border-white rounded-lg 
                                 shadow-[0_0_10px_rgba(255,255,255,0.8),0_0_25px_rgba(0,0,0,1)]">
                        0
                    </span>
                    <span class="text-sm uppercase text-gray-300 mt-2">Hours</span>
                </div>

                <!-- Minutes -->
                <div class="flex flex-col items-center">
                    <span id="minutes-next" 
                          class="text-5xl sm:text-7xl font-extrabold text-white 
                                 bg-transparent backdrop-blur-sm 
                                 w-24 h-24 sm:w-40 sm:h-40 flex items-center justify-center 
                                 border-2 border-white rounded-lg 
                                 shadow-[0_0_10px_rgba(255,255,255,0.8),0_0_25px_rgba(0,0,0,1)]">
                        0
                    </span>
                    <span class="text-sm uppercase text-gray-300 mt-2">Minutes</span>
                </div>

                <!-- Seconds -->
                <div class="flex flex-col items-center">
                    <span id="seconds-next" 
                          class="text-5xl sm:text-7xl font-extrabold text-white 
                                 bg-transparent backdrop-blur-sm 
                                 w-24 h-24 sm:w-40 sm:h-40 flex items-center justify-center 
                                 border-2 border-white rounded-lg 
                                 shadow-[0_0_10px_rgba(255,255,255,0.8),0_0_25px_rgba(0,0,0,1)]">
                        0
                    </span>
                    <span class="text-sm uppercase text-gray-300 mt-2">Seconds</span>
                </div>
            </div>
        </div>
    @else
        <div class="text-3xl font-bold text-red-500">No upcoming race</div>
    @endif
</div>

    <!-- Community Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-6xl mx-auto w-full">
        <!-- Forums -->
        <div class="bg-white/5 border border-white/20 rounded-xl p-6 shadow-lg">
            <h2 class="audiowide-regular text-xl font-bold uppercase mb-4">Forums</h2>
            <p class="mb-4 text-gray-300">Join the conversation with other F1 fans. Share your thoughts, discuss races, and connect with the community.</p>
            <a href="{{ route('forums.index') }}" 
               class="inline-block px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg font-bold uppercase tracking-wide">
                Go to Forums
            </a>
        </div>

        <!-- Predictions -->
        <div class="bg-white/5 border border-white/20 rounded-xl p-6 shadow-lg">
            <h2 class="audiowide-regular text-xl font-bold uppercase mb-4">Make Your Predictions</h2>
            <p class="mb-4 text-gray-300">Think you know who will win the next race? Submit your predictions and see how you stack up against others.</p>
            <a href="{{ route('game.index') }}" 
               class="inline-block px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg font-bold uppercase tracking-wide">
                Predict Now
            </a>
        </div>
    </div>

</div>
<!-- Countdown Script -->
<script>
(function(){
    @if($nextRace)
    const raceDate = new Date("{{ $nextRace['date'] }}T{{ $nextRace['time'] ?? '00:00:00Z' }}").getTime();

    const daysEl = document.getElementById("days-next");
    const hoursEl = document.getElementById("hours-next");
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

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        daysEl.innerHTML = days;
        hoursEl.innerHTML = hours;
        minutesEl.innerHTML = minutes;
        secondsEl.innerHTML = seconds;
    }

    updateCountdown();
    const timer = setInterval(updateCountdown, 1000);
    @endif
})();


    
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
