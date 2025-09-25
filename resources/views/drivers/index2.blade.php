<x-app-layout>
    <!-- Full-width banner -->
    <div class="relative w-full h-[40rem] overflow-hidden">
        <img src="{{ asset('images/racetrack.jpg') }}" 
             alt="Racetrack Banner" 
             class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/40 flex flex-col justify-center items-center text-center text-white">
            <h2 class="text-5xl font-bold audiowide-regular mb-4">
                CURRENT SEASON F1 DRIVERS
            </h2>
            <p class="max-w-2xl text-lg mb-[18rem]">
                Explore the grid of the 2025 Formula 1 season.
            </p>
        </div>
    </div>

    <!-- Driver cards -->
    <div class="max-w-7xl mx-auto px-4">
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($drivers->count())
            <!-- First 3 drivers overlapping the banner -->
            <div class="relative -mt-[18rem] z-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
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

                            <!-- Glow effect on hover -->
                            

                            <div class="relative w-full h-full text-center rounded-2xl">
                                <div class="absolute w-full h-full [backface-visibility:hidden] rounded-2xl overflow-hidden">

                                    <!-- Image with zoom on hover -->
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

                                        <!-- Season Stats split into left/right -->
                                        <div class="flex-1 flex items-center justify-between">
                                            <!-- Left: Stats -->
                                            <div>
                                                <h3 class="text-lg font-semibold mb-2 audiowide-regular text-white">Season Stats</h3>
                                                <ul class="space-y-1 text-base text-white audiowide-regular text-left">
                                                    <li><strong>Position:</strong> {{ $driver->latestStanding->position ?? '—' }}</li>
                                                    <li><strong>Points:</strong> {{ $driver->latestStanding->points ?? '—' }}</li>
                                                    <li><strong>Wins:</strong> {{ $driver->latestStanding->wins ?? '—' }}</li>
                                                </ul>
                                            </div>

                                            <!-- Right: Driver number -->
                                            <div class="text-5xl font-bold audiowide-regular opacity-80">
                                                #{{ $driver->permanent_number ?? '—' }}
                                            </div>
                                        </div>

                                        <!-- Bottom row -->
                                        <div class="flex items-center justify-between text-lg audiowide-regular mt-4">
                                            <p></p>
                                            <p><strong>Team:</strong> {{ $constructorName }}</p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </a>

                @endforeach
            </div>

            <!-- Remaining drivers -->
            <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($drivers->skip(3) as $driver)
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

                            <!-- Glow effect on hover -->
                            <div class="absolute inset-0 rounded-2xl border-2 border-transparent"></div>

                            <div class="relative w-full h-full text-center rounded-2xl">
                                <div class="absolute w-full h-full [backface-visibility:hidden] rounded-2xl overflow-hidden">

                                    <!-- Image with zoom on hover -->
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

                                        <!-- Season Stats split into left/right -->
                                        <div class="flex-1 flex items-center justify-between">
                                            <!-- Left: Stats -->
                                            <div>
                                                <h3 class="text-lg font-semibold mb-2 audiowide-regular text-white">Season Stats</h3>
                                                <ul class="space-y-1 text-base text-white audiowide-regular text-left">
                                                    <li><strong>Position:</strong> {{ $driver->latestStanding->position ?? '—' }}</li>
                                                    <li><strong>Points:</strong> {{ $driver->latestStanding->points ?? '—' }}</li>
                                                    <li><strong>Wins:</strong> {{ $driver->latestStanding->wins ?? '—' }}</li>
                                                </ul>
                                            </div>

                                            <!-- Right: Driver number -->
                                            <div class="text-5xl font-bold audiowide-regular opacity-80">
                                                #{{ $driver->permanent_number ?? '—' }}
                                            </div>
                                        </div>

                                        <!-- Bottom row -->
                                        <div class="flex items-center justify-between text-lg audiowide-regular mt-4">
                                            <p></p>
                                            <p><strong>Team:</strong> {{ $constructorName }}</p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </a>

                @endforeach
            </div>
        @else
            <p class="text-center text-gray-600">
                No drivers found. 
                <a href="{{ route('drivers.sync') }}" class="text-blue-500 hover:underline">Sync now</a>
            </p>
        @endif
    </div>
</x-app-layout>
