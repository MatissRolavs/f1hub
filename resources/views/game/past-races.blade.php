<x-app-layout>
<div class="max-w-4xl mx-auto px-4 py-6 text-white">
    <h2 class="text-2xl font-bold mb-4">Past Races (Test Predictions)</h2>

    @foreach($races as $race)
        <div class="flex justify-between items-center bg-gray-800 p-4 rounded mb-2">
            <div>
                <div class="font-bold">{{ $race['raceName'] }}</div>
                <div class="text-sm text-gray-400">{{ $race['date'] }}</div>
            </div>
            <a href="{{ route('game.playPast', ['season' => $race['season'], 'round' => $race['round']]) }}"
               class="bg-blue-600 px-3 py-1 rounded hover:bg-blue-500">
                Make Test Prediction
            </a>
        </div>
    @endforeach
</div>
</x-app-layout>
