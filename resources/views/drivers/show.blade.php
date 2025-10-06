<x-app-layout>
<div class="max-w-6xl mx-auto px-4 py-8 text-white audiowide-regular">

    <!-- Driver Header -->
    <div class="bg-[#1a1a1a] rounded-xl shadow-lg overflow-hidden flex flex-col md:flex-row items-center md:items-stretch">
        <!-- Left: Driver Info -->
        <div class="flex-1 p-6 flex flex-col justify-center">
            <h2 class="text-4xl font-bold mb-4">
                {{ $driver->given_name }} {{ $driver->family_name }}
            </h2>
            <p class="text-lg"><strong>Number:</strong> {{ $driver->permanent_number ?? '—' }}</p>
            <p class="text-lg"><strong>Nationality:</strong> {{ $driver->nationality }}</p>
            <p class="text-lg"><strong>Team:</strong> {{ $driver->latestStanding->constructor->name ?? '—' }}</p>
        </div>

        <!-- Right: Driver Image -->
        <div class="md:w-1/2">
            <img
                src="https://media.formula1.com/image/upload/f_webp,c_limit,q_50,w_640/content/dam/fom-website/drivers/2025Drivers/{{ $driver->family_name }}"
                alt="{{ $driver->given_name }} {{ $driver->family_name }}"
                class="w-full h-full object-cover"
                onerror="this.onerror=null;this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/480px-No_image_available.svg.png';"
            >
        </div>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
        <!-- Current Season Stats -->
        <div class="bg-[#1a1a1a] rounded-xl shadow-lg p-6">
            <h3 class="text-2xl font-bold mb-4">Current Season</h3>
            <ul class="space-y-2 text-gray-300">
                <li><strong>Season Position:</strong> {{ $seasonStats['position'] ?? '—' }}</li>
                <li><strong>Season Points:</strong> {{ $seasonStats['points'] ?? '—' }}</li>
                <li><strong>Grand Prix Entries:</strong> {{ $seasonStats['entries'] ?? '—' }}</li>
                <li><strong>Grand Prix Wins:</strong> {{ $seasonStats['wins'] ?? '—' }}</li>
            </ul>
        </div>

        <!-- Career Stats -->
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

</div>
</x-app-layout>
