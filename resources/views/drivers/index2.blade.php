<x-app-layout>
<div class="max-w-7xl mx-auto px-4 py-8 space-y-9">
    <h2 class="text-2xl font-bold text-center text-white audiowide-regular">
        CURRENT SEASON F1 DRIVERS
    </h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($drivers->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($drivers as $driver)
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
                    <div class="group [perspective:1000px] rounded-2xl shadow-lg overflow-hidden transition-all duration-300 transform"
                         style="background-color: {{ $bgColor }}; height: 740px;">
                        <div class="relative w-full h-full text-center transition-transform duration-[800ms] [transform-style:preserve-3d] group-hover:[transform:rotateY(180deg)] rounded-2xl">
                            
                            {{-- Front Side --}}
                            <div class="absolute w-full h-full [backface-visibility:hidden] rounded-2xl overflow-hidden">
                                <img 
                                    src="https://media.formula1.com/image/upload/f_webp,c_limit,q_50,w_640/content/dam/fom-website/drivers/2025Drivers/{{ $driver->family_name }}" 
                                    alt="{{ $driver->given_name }} {{ $driver->family_name }}"
                                    class="w-full h-[520px] object-cover bg-white rounded-t-2xl"
                                    onerror="this.onerror=null;this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/480px-No_image_available.svg.png';"
                                >
                                <div class="p-6 text-white min-h-[200px] flex flex-col justify-between rounded-b-2xl">
                                    <!-- Name + Flag -->
                                    <div class="flex items-center justify-between mb-3">
                                        <h5 class="text-xl font-bold leading-tight audiowide-regular">
                                            {{ $driver->given_name }} {{ $driver->family_name }}
                                        </h5>
                                        <img src="{{ $flagUrl }}" alt="{{ $driver->nationality }}" class="w-8 h-5 rounded shadow">
                                    </div>

                                    <!-- Number (left) + Team (right) -->
                                    <div class="flex items-center justify-between text-lg audiowide-regular">
                                        <p><strong>Number:</strong> {{ $driver->permanent_number ?? '—' }}</p>
                                        <p><strong>Team:</strong> {{ $constructorName }}</p>
                                    </div>
                                </div>


                            </div>

                            {{-- Back Side --}}
                            <div class="absolute w-full h-full [backface-visibility:hidden] [transform:rotateY(180deg)] flex flex-col justify-center items-center p-6 rounded-2xl"
                                 style="background-color: {{ $bgColor }};">
                                <h3 class="text-xl font-semibold mb-4 audiowide-regular text-white">Season Stats</h3>
                                <ul class="space-y-2 text-base text-white audiowide-regular">
                                    <li><strong>Position:</strong> {{ $driver->latestStanding->position ?? '—' }}</li>
                                    <li><strong>Points:</strong> {{ $driver->latestStanding->points ?? '—' }}</li>
                                    <li><strong>Wins:</strong> {{ $driver->latestStanding->wins ?? '—' }}</li>
                                </ul>
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
