@php
    $standing        = $driver->standings->first();
    $constructorName = $standing->constructor->name ?? 'Unknown';
    $bgColor         = config('f1.team_colors.' . $constructorName, config('f1.default_team_color'));
    $flagCode        = config('f1.nationality_flags.' . $driver->nationality, config('f1.default_flag_code'));
    $flagUrl         = "https://flagcdn.com/w40/" . $flagCode . ".png";
    $position        = $standing->position ?? null;
    $rankClass       = match(true) {
        $position === 1 => 'bg-gradient-to-br from-yellow-300 to-yellow-600 text-black',
        $position === 2 => 'bg-gradient-to-br from-gray-200 to-gray-500 text-black',
        $position === 3 => 'bg-gradient-to-br from-orange-400 to-orange-700 text-black',
        default         => 'bg-black/70 text-white',
    };
@endphp

<a href="{{ route('drivers.show', $driver) }}" class="block reveal">
    <div class="f1-card group relative rounded-2xl shadow-lg overflow-hidden"
         style="background-color: {{ $bgColor }}; height: 740px;">

        {{-- Rank badge --}}
        @if($position)
            <div class="absolute top-4 left-4 z-20">
                <span class="inline-flex items-center justify-center w-14 h-14 rounded-full text-2xl font-extrabold audiowide-regular border-2 border-white/30 shadow-lg {{ $rankClass }}">
                    P{{ $position }}
                </span>
            </div>
        @endif

        {{-- Diagonal stripe accent --}}
        <div class="absolute top-0 right-0 w-24 h-24 overflow-hidden z-10 pointer-events-none">
            <div class="absolute -right-12 top-4 w-40 h-6 bg-black/25 rotate-45"></div>
        </div>

        <div class="relative w-full h-full text-center rounded-2xl">
            <div class="absolute w-full h-full [backface-visibility:hidden] rounded-2xl overflow-hidden">

                <div class="overflow-hidden rounded-t-2xl">
                    <img
                        src="https://media.formula1.com/image/upload/f_webp,c_limit,q_50,w_640/content/dam/fom-website/drivers/{{ $selectedSeason }}Drivers/{{ $driver->family_name }}"
                        alt="{{ $driver->given_name }} {{ $driver->family_name }}"
                        class="w-full h-[520px] object-cover bg-white rounded-t-2xl transform transition-transform duration-700 group-hover:scale-110"
                        onerror="this.onerror=null;this.src='data:image/png;base64,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';"
                    >
                </div>

                <div class="p-6 text-white flex flex-col h-[220px] rounded-b-2xl">
                    <div class="flex items-center justify-between mb-3">
                        <h5 class="text-xl font-bold leading-tight audiowide-regular">
                            {{ $driver->given_name }} {{ $driver->family_name }}
                        </h5>
                        <img src="{{ $flagUrl }}" alt="{{ $driver->nationality }}" class="w-8 h-5 rounded shadow">
                    </div>

                    <div class="flex-1 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-2 audiowide-regular text-white">Season Stats</h3>
                            <ul class="space-y-1 text-base text-white audiowide-regular text-left">
                                <li><strong>Position:</strong> {{ $standing->position ?? '—' }}</li>
                                <li><strong>Points:</strong> {{ $standing->points ?? '—' }}</li>
                                <li><strong>Wins:</strong> {{ $standing->wins ?? '—' }}</li>
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
        </div>
    </div>
</a>
