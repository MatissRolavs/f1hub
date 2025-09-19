<x-app-layout>
<div class="max-w-4xl mx-auto px-4 py-6 text-white">
    <h2 class="text-2xl font-bold mb-4">My Predictions</h2>

    @forelse($predictions as $pred)
        <div class="bg-gray-800 p-4 rounded mb-2">
            <div class="font-bold">{{ $pred->raceName }}</div>
            <div class="text-sm text-gray-400">Race Date: {{ $pred->raceDate }}</div>
            <div class="mt-2">
                <strong>Your Prediction:</strong>
                {{ implode(', ', json_decode($pred->predicted_order, true)) }}
            </div>
            @if($pred->raceDate && \Carbon\Carbon::parse($pred->raceDate)->isFuture())
                <a href="{{ route('game.play', ['season' => $pred->season, 'round' => $pred->round]) }}"
                   class="bg-blue-600 px-3 py-1 rounded hover:bg-blue-500 mt-2 inline-block">
                    Edit Prediction
                </a>
            @else
                <span class="text-gray-400 text-sm">Race locked</span>
            @endif
        </div>
    @empty
        <p>You haven't made any predictions yet.</p>
    @endforelse
</div>
</x-app-layout>
