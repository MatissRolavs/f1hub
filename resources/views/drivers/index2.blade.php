<x-app-layout>
<style>
.flip-card {
  perspective: 1000px;
}
.flip-card-inner {
  position: relative;
  width: 100%;
  height: 100%;
  text-align: center;
  transition: transform 0.8s;
  transform-style: preserve-3d;
}
.flip-card:hover .flip-card-inner {
  transform: rotateY(180deg);
}
.flip-card-front, .flip-card-back {
  position: absolute;
  width: 100%;
  height: 100%;
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
}
.flip-card-back {
  background-color: transparent;
  color: white;
  transform: rotateY(180deg);
}
</style>

<div class="max-w-7xl mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold text-center text-white audiowide-regular">CURRENT SEASON F1 DRIVERS</h2>
    <br>
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">{{ session('success') }}</div>
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
                        'Australian' => 'au', 'Austrian' => 'at', 'Belgian' => 'be', 'Brazilian' => 'br',
                        'British' => 'gb', 'Canadian' => 'ca', 'Chinese' => 'cn', 'Danish' => 'dk',
                        'Dutch' => 'nl', 'Finnish' => 'fi', 'French' => 'fr', 'German' => 'de',
                        'Italian' => 'it', 'Japanese' => 'jp', 'Mexican' => 'mx', 'Monegasque' => 'mc',
                        'New Zealander' => 'nz', 'Polish' => 'pl', 'Portuguese' => 'pt', 'Russian' => 'ru',
                        'Spanish' => 'es', 'Swedish' => 'se', 'Swiss' => 'ch', 'Thai' => 'th',
                        'Turkish' => 'tr', 'American' => 'us', 'Czech' => 'cz', 'South African' => 'za',
                        'Argentine' => 'ar', 'Indian' => 'in', 'Irish' => 'ie', 'Ukrainian' => 'ua',
                        'Estonian' => 'ee', 'Latvian' => 'lv', 'Lithuanian' => 'lt',
                    ];
                    $flagCode = $nationalityMap[$driver->nationality] ?? 'xx';
                    $flagUrl = "https://flagcdn.com/w40/" . $flagCode . ".png";
                @endphp

                <a href="{{ route('drivers.show', $driver) }}" class="block">
                    <div class="flip-card rounded-2xl shadow-lg overflow-hidden transition-all duration-300 transform"
                         style="background-color: {{ $bgColor }}; height: 740px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"
                         onmouseover="this.style.boxShadow='0 0 20px {{ $bgColor }}';" 
                         onmouseout="this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)';">
                        <div class="flip-card-inner rounded-2xl">
                            {{-- Front Side --}}
                            <div class="flip-card-front rounded-2xl overflow-hidden">
                                <img 
                                    src="https://media.formula1.com/image/upload/f_webp,c_limit,q_50,w_640/content/dam/fom-website/drivers/2025Drivers/{{ $driver->family_name }}" 
                                    alt="{{ $driver->given_name }} {{ $driver->family_name }}"
                                    class="w-full h-[520px] object-cover bg-white rounded-t-2xl"
                                    onerror="this.onerror=null;this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/480px-No_image_available.svg.png';"
                                >
                                <div class="p-4 text-white h-[100px] flex flex-col justify-between rounded-b-2xl">
                                    <div class="flex items-center justify-between mb-1">
                                        <h5 class="text-base font-semibold leading-tight audiowide-regular">
                                            {{ $driver->given_name }} {{ $driver->family_name }}
                                        </h5>
                                        <img src="{{ $flagUrl }}" alt="{{ $driver->nationality }}" class="w-6 h-4 rounded shadow">
                                    </div>
                                    <p class="text-sm audiowide-regular"><strong>Number:</strong> {{ $driver->permanent_number ?? '—' }}</p>
                                    <p class="text-sm audiowide-regular"><strong>Team:</strong> {{ $constructorName }}</p>
                                </div>
                            </div>
                            {{-- Back Side --}}
                            <div class="flip-card-back rounded-2xl flex flex-col justify-center items-center p-6"
                                 style="background-color: {{ $bgColor }};">
                                <h3 class="text-xl font-semibold mb-4 audiowide-regular">Season Stats</h3>
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
        <p class="text-center text-gray-600">No drivers found. <a href="{{ route('drivers.sync') }}" class="text-blue-500 hover:underline">Sync now</a></p>
    @endif
</div>
</x-app-layout>
