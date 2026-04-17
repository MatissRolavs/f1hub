<x-app-layout>
<div class="max-w-6xl mx-auto px-4 py-8 text-white audiowide-regular">

    <!-- Driver Header -->
    <div class="bg-[#1a1a1a] rounded-xl shadow-lg overflow-hidden flex flex-col md:flex-row items-center md:items-stretch">
        <div class="flex-1 p-6 flex flex-col justify-center">
            <h2 class="text-4xl font-bold mb-4">
                {{ $driver->given_name }} {{ $driver->family_name }}
            </h2>
            <p class="text-lg"><strong>Number:</strong> {{ $driver->permanent_number ?? '—' }}</p>
            <p class="text-lg"><strong>Nationality:</strong> {{ $driver->nationality }}</p>
            <p class="text-lg"><strong>Team:</strong> {{ $driver->latestStanding->constructor->name ?? '—' }}</p>
        </div>

        <div class="md:w-1/2">
            <img
                src="https://media.formula1.com/image/upload/f_webp,c_limit,q_50,w_640/content/dam/fom-website/drivers/2025Drivers/{{ $driver->family_name }}"
                alt="{{ $driver->given_name }} {{ $driver->family_name }}"
                class="w-full h-full object-cover"
                onerror="this.onerror=null;this.src='data:image/png;base64,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';"
            >
        </div>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
        <div class="bg-[#1a1a1a] rounded-xl shadow-lg p-6">
            <h3 class="text-2xl font-bold mb-4">Current Season</h3>
            <ul class="space-y-2 text-gray-300">
                <li><strong>Season Position:</strong> {{ $seasonStats['position'] ?? '—' }}</li>
                <li><strong>Season Points:</strong> {{ $seasonStats['points'] ?? '—' }}</li>
                <li><strong>Grand Prix Entries:</strong> {{ $seasonStats['entries'] ?? '—' }}</li>
                <li><strong>Grand Prix Wins:</strong> {{ $seasonStats['wins'] ?? '—' }}</li>
            </ul>
        </div>

        <div class="bg-[#1a1a1a] rounded-xl shadow-lg p-6">
            <h3 class="text-2xl font-bold mb-4">Career Stats</h3>
            <ul class="space-y-2 text-gray-300">
                <li><strong>Grand Prix Entered:</strong> {{ number_format($careerStats['races']) }}</li>
                <li><strong>Career Points:</strong> {{ rtrim(rtrim(number_format($careerStats['points'], 1), '0'), '.') }}</li>
                <li><strong>Career Wins:</strong> {{ number_format($careerStats['wins']) }}</li>
                <li><strong>Podiums:</strong> {{ number_format($careerStats['podiums']) }}</li>
                <li><strong>Pole Positions:</strong> {{ number_format($careerStats['poles']) }}</li>
                <li><strong>Fastest Laps:</strong> {{ number_format($careerStats['fastest_laps']) }}</li>
                <li><strong>DNFs:</strong> {{ number_format($careerStats['dnfs']) }}</li>
            </ul>
        </div>
    </div>

    <!-- Compare Section -->
    <div class="mt-10 text-center">
        <h3 class="text-xl font-bold mb-4">Compare Stats</h3>

        <div class="flex flex-col md:flex-row justify-center gap-4">
            <!-- Compare with previous season -->
            <a href="{{ route('drivers.compare', ['driver' => $driver->id, 'type' => 'season', 'season_a' => date('Y'), 'season_b' => date('Y') - 1]) }}"
            class="px-6 py-3 bg-gray-800 rounded-lg shadow hover:bg-gray-700 transition">
            Compare with Previous Season
            </a>
    </div>
</div>
</div>
</x-app-layout>
