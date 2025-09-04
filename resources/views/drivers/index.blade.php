<x-app-layout>
<div class="max-w-7xl mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold mb-6 text-center">All Current Season Active F1 Drivers</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">{{ session('success') }}</div>
    @endif

    @if($drivers->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($drivers as $driver)
                @php
                    $givenParts = explode(' ', $driver->given_name);
                    $givenForUrl = count($givenParts) > 1 ? $givenParts[1] : $givenParts[0];
                    $givenForUrl = Str::lower(Str::ascii($givenForUrl));
                    $familyForUrl = Str::lower(Str::ascii($driver->family_name));

                    // Team colors (simplified example)
                    $teamColors = [
                        'Red Bull' => '#1E40AF',
                        'Ferrari' => '#B91C1C',
                        'Mercedes' => '#059669',
                        'McLaren' => '#F59E0B',
                        'Alpine' => '#3B82F6',
                        'Aston Martin' => '#065F46',
                        'Williams' => '#2563EB',
                        'AlphaTauri' => '#4B5563',
                        'Alfa Romeo' => '#991B1B',
                        'Haas' => '#374151',
                    ];
                    $bgColor = $teamColors[$driver->team] ?? '#E5E7EB';

                    // Country flag (using country code)
                    $flagUrl = "https://flagcdn.com/w40/" . strtolower($driver->nationality_code) . ".png";
                @endphp

                <div class="rounded-lg shadow-lg overflow-hidden transform transition duration-300 hover:scale-105" style="background-color: {{ $bgColor }};">
                    <img 
                        src="https://www.kymillman.com/wp-content/uploads/f1/pages/driver-profiles/driver-faces/{{ $givenForUrl }}-{{ $familyForUrl }}-f1-driver-profile-picture.png" 
                        alt="{{ $driver->given_name }} {{ $driver->family_name }}"
                        class="w-full h-48 object-contain bg-white p-4"
                    >

                    <div class="p-4 text-white">
                        <div class="flex items-center justify-between mb-2">
                            <h5 class="text-lg font-semibold">
                                <a href="{{ $driver->url }}" target="_blank" class="hover:underline">
                                    {{ $driver->given_name }} {{ $driver->family_name }}
                                </a>
                            </h5>
                            <img src="{{ $flagUrl }}" alt="{{ $driver->nationality }}" class="w-6 h-4 rounded shadow">
                        </div>
                        <p class="text-sm"><strong>Number:</strong> {{ $driver->permanent_number ?? '—' }}</p>
                        <p class="text-sm"><strong>Team:</strong> {{ $driver->team }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-center text-gray-600">No drivers found. <a href="{{ route('drivers.sync') }}" class="text-blue-500 hover:underline">Sync now</a></p>
    @endif
</div>
</x-app-layout>
