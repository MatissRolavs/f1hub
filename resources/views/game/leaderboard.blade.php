<x-app-layout>
<div class="max-w-3xl mx-auto px-4 py-6 text-white">
    <h2 class="text-2xl font-bold mb-4">🏆 Leaderboard</h2>

    @if($leaderboard->isEmpty())
        <p>No scores yet.</p>
    @else
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-800">
                    <th class="p-2 text-left">Rank</th>
                    <th class="p-2 text-left">Player</th>
                    <th class="p-2 text-left">Total Score</th>
                    <th class="p-2 text-left">Races Played</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leaderboard as $index => $player)
                    <tr class="{{ $index % 2 === 0 ? 'bg-gray-700' : 'bg-gray-600' }}">
                        <td class="p-2">{{ $index + 1 }}</td>
                        <td class="p-2 font-bold">{{ $player->player_name }}</td>
                        <td class="p-2">{{ $player->total_score }}</td>
                        <td class="p-2">{{ $player->races_played }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
</x-app-layout>
