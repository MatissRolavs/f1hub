<x-app-layout>
<div class="max-w-4xl mx-auto px-4 py-6 text-white">
    <h2 class="text-2xl font-bold mb-4">Prediction Results</h2>

    @forelse($scores as $score)
        <div class="bg-gray-800 p-4 rounded mb-2">
            <div class="font-bold">{{ $score->race_name }}</div>
            <div class="text-sm text-gray-400">Score: {{ $score->score }} / {{ $score->total }}</div>
        </div>
    @empty
        <p>No results available yet.</p>
    @endforelse
</div>
</x-app-layout>
