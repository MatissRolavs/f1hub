<x-app-layout>
<div class="max-w-6xl mx-auto px-4 py-8 text-white audiowide-regular">

    <h2 class="text-3xl font-bold mb-6">Driver Comparison: {{ $driver->given_name }} {{ $driver->family_name }}</h2>

    <!-- Comparison Form -->
    <form method="GET" action="{{ route('drivers.compare', $driver) }}" class="mb-8 flex flex-col md:flex-row gap-4 items-end">
        <!-- Season A (Current) -->
        <div>
            <label class="block text-gray-300 font-semibold mb-1">Season A (Current)</label>
            <input type="text" name="season_a" value="{{ $seasonA }}" readonly
                   class="bg-gray-800 text-white px-3 py-2 rounded w-40 cursor-not-allowed">
        </div>

        <!-- Season B Dropdown -->
        <div>
            <label class="block text-gray-300 font-semibold mb-1">Season B</label>
            <select name="season_b" class="bg-gray-800 text-white px-3 py-2 rounded w-40">
                @foreach($seasonsB as $season)
                    <option value="{{ $season }}" @if($season == $seasonB) selected @endif>{{ $season }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-bold">
            Compare
        </button>
    </form>

    <!-- Comparison Results -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Season A -->
        <div class="bg-[#1a1a1a] rounded-xl shadow-lg p-6">
            <h3 class="text-2xl font-bold mb-4">Season {{ $seasonA }}</h3>
            <ul class="space-y-2 text-gray-300">
                <li><strong>Season Position:</strong> {{ $statsA['position'] ?? '—' }}</li>
                <li><strong>Season Points:</strong> {{ $statsA['points'] ?? '—' }}</li>
                <li><strong>Grand Prix Entries:</strong> {{ $statsA['entries'] ?? '—' }}</li>
                <li><strong>Grand Prix Wins:</strong> {{ $statsA['wins'] ?? '—' }}</li>
            </ul>
        </div>

        <!-- Season B -->
        <div class="bg-[#1a1a1a] rounded-xl shadow-lg p-6">
            <h3 class="text-2xl font-bold mb-4">Season {{ $seasonB }}</h3>
            <ul class="space-y-2 text-gray-300">
                <li><strong>Season Position:</strong> {{ $statsB['position'] ?? '—' }}</li>
                <li><strong>Season Points:</strong> {{ $statsB['points'] ?? '—' }}</li>
                <li><strong>Grand Prix Entries:</strong> {{ $statsB['entries'] ?? '—' }}</li>
                <li><strong>Grand Prix Wins:</strong> {{ $statsB['wins'] ?? '—' }}</li>
            </ul>
        </div>
    </div>
</div>
</x-app-layout>
