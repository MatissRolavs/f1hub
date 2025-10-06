<x-app-layout>
<div class="px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto mt-8">
    <div class="bg-[#1a1a1a] rounded-xl shadow-lg text-white audiowide-regular p-8">
        <h2 class="text-4xl font-bold mb-8 text-center audiowide-regular">🏆 Leaderboard</h2>

        @if($leaderboard->isEmpty())
            <p class="text-center text-gray-400">No scores yet.</p>
        @else
            <!-- Podium -->
            <div class="flex flex-col md:flex-row items-center md:items-end justify-center gap-8 mb-10">
                <!-- 1st Place -->
                @if(isset($leaderboard[0]))
                <div class="flex flex-col items-center order-1 md:order-2">
                    <div class="bg-gradient-to-t from-yellow-600 to-yellow-300 text-black w-36 h-56 flex flex-col justify-end items-center rounded-t-lg shadow-xl">
                        <span class="text-4xl mb-1">1</span>
                        <span class="font-bold">{{ $leaderboard[0]->player_name }}</span>
                        <span class="text-sm">{{ $leaderboard[0]->total_score }} pts</span>
                        <span class="text-xs text-gray-800">Races: {{ $leaderboard[0]->races_played }}</span>
                    </div>
                </div>
                @endif

                <!-- 2nd Place -->
                @if(isset($leaderboard[1]))
                <div class="flex flex-col items-center order-2 md:order-1">
                    <div class="bg-gradient-to-t from-gray-700 to-gray-400 w-32 h-44 flex flex-col justify-end items-center rounded-t-lg shadow-lg">
                        <span class="text-3xl mb-1">2</span>
                        <span class="font-semibold">{{ $leaderboard[1]->player_name }}</span>
                        <span class="text-sm text-gray-200">{{ $leaderboard[1]->total_score }} pts</span>
                        <span class="text-xs text-gray-300">Races: {{ $leaderboard[1]->races_played }}</span>
                    </div>
                </div>
                @endif

                <!-- 3rd Place -->
                @if(isset($leaderboard[2]))
                <div class="flex flex-col items-center order-3 md:order-3">
                    <div class="bg-gradient-to-t from-orange-700 to-orange-400 w-32 h-36 flex flex-col justify-end items-center rounded-t-lg shadow-lg">
                        <span class="text-3xl mb-1">3</span>
                        <span class="font-semibold">{{ $leaderboard[2]->player_name }}</span>
                        <span class="text-sm text-gray-200">{{ $leaderboard[2]->total_score }} pts</span>
                        <span class="text-xs text-gray-300">Races: {{ $leaderboard[2]->races_played }}</span>
                    </div>
                </div>
                @endif
            </div>


            <!-- Remaining Players -->
            @if(count($leaderboard) > 3)
            <table class="w-full border-collapse md:table block">
                <thead class="hidden md:table-header-group">
                    <tr>
                        <th class="bg-[#222] text-gray-400 uppercase text-sm p-3 text-left">Rank</th>
                        <th class="bg-[#222] text-gray-400 uppercase text-sm p-3 text-left">Player</th>
                        <th class="bg-[#222] text-gray-400 uppercase text-sm p-3 text-left">Total Score</th>
                        <th class="bg-[#222] text-gray-400 uppercase text-sm p-3 text-left">Races Played</th>
                    </tr>
                </thead>
                <tbody class="block md:table-row-group">
                    @foreach($leaderboard->slice(3) as $index => $player)
                        <tr class="block md:table-row mb-4 md:mb-0 even:bg-[#2a2a2a] hover:bg-[#333] rounded-lg md:rounded-none p-3 md:p-0">
                            <td data-label="Rank" class="block md:table-cell p-2 md:p-3">
                                <span class="block font-bold text-gray-400 md:hidden">Rank</span>
                                <span>{{ $index + 1 }}</span>
                            </td>
                            <td data-label="Player" class="block md:table-cell p-2 md:p-3">
                                <span class="block font-bold text-gray-400 md:hidden">Player</span>
                                <span class="font-bold">{{ $player->player_name }}</span>
                            </td>
                            <td data-label="Total Score" class="block md:table-cell p-2 md:p-3">
                                <span class="block font-bold text-gray-400 md:hidden">Total Score</span>
                                <span>{{ $player->total_score }}</span>
                            </td>
                            <td data-label="Races Played" class="block md:table-cell p-2 md:p-3">
                                <span class="block font-bold text-gray-400 md:hidden">Races Played</span>
                                <span>{{ $player->races_played }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        @endif
    </div>
</div>
</x-app-layout>
