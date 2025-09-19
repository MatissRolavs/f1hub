<x-app-layout>
<div class="max-w-5xl mx-auto px-4 py-6 text-white audiowide-regular">

    <h2 class="text-3xl font-bold mb-4">
        {{ $driver->given_name }} {{ $driver->family_name }}
    </h2>

    <div class="flex flex-col md:flex-row gap-6">
        <img
            src="https://media.formula1.com/image/upload/f_webp,c_limit,q_50,w_640/content/dam/fom-website/drivers/2025Drivers/{{ $driver->family_name }}"
            alt="{{ $driver->given_name }} {{ $driver->family_name }}"
            class="w-full md:w-1/3 object-cover rounded-lg bg-white"
            onerror="this.onerror=null;this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/480px-No_image_available.svg.png';"
        >
        <div class="flex-1 space-y-2">
            <p><strong>Number:</strong> {{ $driver->permanent_number ?? '—' }}</p>
            <p><strong>Nationality:</strong> {{ $driver->nationality }}</p>
            <p><strong>Team:</strong> {{ $driver->latestStanding->constructor->name ?? '—' }}</p>
            <h3 class="text-xl font-bold mb-4">Career stats</h3>
            <ul class="space-y-2">
                <li><strong>Grand Prix entered:</strong> {{ number_format($careerStats['races']) }}</li>
                <li><strong>Career points:</strong> {{ rtrim(rtrim(number_format($careerStats['points'], 1), '0'), '.') }}</li>
                <li><strong>Wins:</strong> {{ number_format($careerStats['wins']) }}</li>
                <li><strong>Podiums:</strong> {{ number_format($careerStats['podiums']) }}</li>
                <li><strong>Pole positions:</strong> {{ number_format($careerStats['poles']) }}</li>
                <li><strong>Fastest laps:</strong> {{ number_format($careerStats['fastest_laps']) }}</li>
                <li><strong>DNFs:</strong> {{ number_format($careerStats['dnfs']) }}</li>
            </ul>
        </div>
    </div>

</div>
</x-app-layout>
