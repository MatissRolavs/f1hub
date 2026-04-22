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
                @php $tc0 = config('f1.team_colors.' . ($leaderboard[0]->constructor_name ?? ''), '#ca8a04'); @endphp
                <div class="flex flex-col items-center order-1 md:order-2">
                    <div class="bg-gradient-to-t from-yellow-600 to-yellow-300 text-black w-36 h-56 flex flex-col justify-end items-center rounded-t-lg shadow-xl pb-3"
                         style="border-top: 4px solid {{ $tc0 }}; box-shadow: 0 0 24px {{ $tc0 }}55;">
                        <span class="text-4xl mb-1">1</span>
                        <span class="font-bold text-center px-2">{{ $leaderboard[0]->player_name }}</span>
                        @if($leaderboard[0]->constructor_name)
                            <span class="text-xs font-bold mt-0.5" style="color:{{ $tc0 }};">{{ $leaderboard[0]->constructor_name }}</span>
                        @endif
                        @if($leaderboard[0]->driver_name && trim($leaderboard[0]->driver_name))
                            <span class="text-xs text-gray-700 mt-0.5">🏎 {{ $leaderboard[0]->driver_name }}</span>
                        @endif
                        <span class="text-sm mt-1">{{ $leaderboard[0]->total_score }} pts</span>
                        <span class="text-xs text-gray-800">Races: {{ $leaderboard[0]->races_played }}</span>
                    </div>
                </div>
                @endif

                <!-- 2nd Place -->
                @if(isset($leaderboard[1]))
                @php $tc1 = config('f1.team_colors.' . ($leaderboard[1]->constructor_name ?? ''), '#6b7280'); @endphp
                <div class="flex flex-col items-center order-2 md:order-1">
                    <div class="bg-gradient-to-t from-gray-700 to-gray-400 w-32 h-44 flex flex-col justify-end items-center rounded-t-lg shadow-lg pb-3"
                         style="border-top: 4px solid {{ $tc1 }}; box-shadow: 0 0 18px {{ $tc1 }}44;">
                        <span class="text-3xl mb-1">2</span>
                        <span class="font-semibold text-center px-2 text-sm">{{ $leaderboard[1]->player_name }}</span>
                        @if($leaderboard[1]->constructor_name)
                            <span class="text-xs font-bold mt-0.5" style="color:{{ $tc1 }};">{{ $leaderboard[1]->constructor_name }}</span>
                        @endif
                        @if($leaderboard[1]->driver_name && trim($leaderboard[1]->driver_name))
                            <span class="text-xs text-gray-300 mt-0.5">🏎 {{ $leaderboard[1]->driver_name }}</span>
                        @endif
                        <span class="text-sm text-gray-200 mt-1">{{ $leaderboard[1]->total_score }} pts</span>
                        <span class="text-xs text-gray-300">Races: {{ $leaderboard[1]->races_played }}</span>
                    </div>
                </div>
                @endif

                <!-- 3rd Place -->
                @if(isset($leaderboard[2]))
                @php $tc2 = config('f1.team_colors.' . ($leaderboard[2]->constructor_name ?? ''), '#ea580c'); @endphp
                <div class="flex flex-col items-center order-3 md:order-3">
                    <div class="bg-gradient-to-t from-orange-700 to-orange-400 w-32 h-36 flex flex-col justify-end items-center rounded-t-lg shadow-lg pb-3"
                         style="border-top: 4px solid {{ $tc2 }}; box-shadow: 0 0 18px {{ $tc2 }}44;">
                        <span class="text-3xl mb-1">3</span>
                        <span class="font-semibold text-center px-2 text-sm">{{ $leaderboard[2]->player_name }}</span>
                        @if($leaderboard[2]->constructor_name)
                            <span class="text-xs font-bold mt-0.5" style="color:{{ $tc2 }};">{{ $leaderboard[2]->constructor_name }}</span>
                        @endif
                        @if($leaderboard[2]->driver_name && trim($leaderboard[2]->driver_name))
                            <span class="text-xs text-gray-200 mt-0.5">🏎 {{ $leaderboard[2]->driver_name }}</span>
                        @endif
                        <span class="text-sm text-gray-200 mt-1">{{ $leaderboard[2]->total_score }} pts</span>
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
                        @php $tc = config('f1.team_colors.' . ($player->constructor_name ?? ''), '#444'); @endphp
                        <tr class="block md:table-row mb-4 md:mb-0 even:bg-[#2a2a2a] hover:bg-[#333] rounded-lg md:rounded-none p-3 md:p-0"
                            style="border-left: 3px solid {{ $tc }};">
                            <td data-label="Rank" class="block md:table-cell p-2 md:p-3">
                                <span class="block font-bold text-gray-400 md:hidden">Rank</span>
                                <span>{{ $index + 4 }}</span>
                            </td>
                            <td data-label="Player" class="block md:table-cell p-2 md:p-3">
                                <span class="block font-bold text-gray-400 md:hidden">Player</span>
                                <div class="flex items-center gap-2">
                                    <span class="inline-block w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:{{ $tc }};box-shadow:0 0 6px {{ $tc }}99;"></span>
                                    <span class="font-bold">{{ $player->player_name }}</span>
                                </div>
                                @if($player->constructor_name)
                                    <div class="text-xs mt-0.5 pl-4 font-semibold" style="color:{{ $tc }};">{{ $player->constructor_name }}</div>
                                @endif
                                @if($player->driver_name && trim($player->driver_name))
                                    <div class="text-xs text-gray-500 mt-0.5 pl-4">🏎 {{ $player->driver_name }}</div>
                                @endif
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
