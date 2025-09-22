<x-app-layout>
<div class="px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto mt-8">
    <div class="bg-[#1a1a1a] rounded-xl shadow-lg text-white font-mono p-8">
        <h2 class="text-2xl font-bold mb-2 text-center audiowide-regular">
            🏁 {{ $raceName }} — {{ $season }}
        </h2>
        <p class="text-center text-gray-400 mb-6 audiowide-regular">
            Round {{ $round }} — {{ \Carbon\Carbon::parse($raceDate)->format('D, d M Y') }}
        </p>

        <table class="w-full border-collapse md:table block">
            <thead class="hidden md:table-header-group">
                <tr>
                    <th class="bg-[#222] text-gray-400 uppercase text-sm p-3 text-left">Pos</th>
                    <th class="bg-[#222] text-gray-400 uppercase text-sm p-3 text-left">Driver</th>
                    <th class="bg-[#222] text-gray-400 uppercase text-sm p-3 text-left">Nationality</th>
                    <th class="bg-[#222] text-gray-400 uppercase text-sm p-3 text-left">Constructor</th>
                    <th class="bg-[#222] text-gray-400 uppercase text-sm p-3 text-left">Grid</th>
                    <th class="bg-[#222] text-gray-400 uppercase text-sm p-3 text-left">Laps</th>
                    <th class="bg-[#222] text-gray-400 uppercase text-sm p-3 text-left">Time</th>
                    <th class="bg-[#222] text-gray-400 uppercase text-sm p-3 text-left">Points</th>
                    <th class="bg-[#222] text-gray-400 uppercase text-sm p-3 text-left">Fastest Lap</th>
                </tr>
            </thead>
            <tbody class="block md:table-row-group">
                @foreach($results as $row)
                    <tr class="block md:table-row mb-4 md:mb-0 even:bg-[#2a2a2a] hover:bg-[#333] rounded-lg md:rounded-none p-3 md:p-0">
                        <td class="block md:table-cell p-2 md:p-3">
                            <span class="block font-bold text-gray-400 md:hidden">Pos</span>
                            <span>{{ $row->position_text }}</span>
                        </td>
                        <td class="block md:table-cell p-2 md:p-3">
                            <span class="block font-bold text-gray-400 md:hidden">Driver</span>
                            <span class="font-bold">{{ $row->given_name }} {{ $row->family_name }}</span>
                            @if($row->code)
                                <span class="text-gray-400 text-sm">({{ $row->code }})</span>
                            @endif
                        </td>
                        <td class="block md:table-cell p-2 md:p-3">
                            <span class="block font-bold text-gray-400 md:hidden">Nationality</span>
                            <span>{{ $row->driver_nationality }}</span>
                        </td>
                        <td class="block md:table-cell p-2 md:p-3">
                            <span class="block font-bold text-gray-400 md:hidden">Constructor</span>
                            <span>{{ $row->constructor_name }}</span>
                        </td>
                        <td class="block md:table-cell p-2 md:p-3">
                            <span class="block font-bold text-gray-400 md:hidden">Grid</span>
                            <span>{{ $row->grid }}</span>
                        </td>
                        <td class="block md:table-cell p-2 md:p-3">
                            <span class="block font-bold text-gray-400 md:hidden">Laps</span>
                            <span>{{ $row->laps }}</span>
                        </td>
                        <td class="block md:table-cell p-2 md:p-3">
                            <span class="block font-bold text-gray-400 md:hidden">Time</span>
                            <span>{{ $row->race_time ?? '—' }}</span>
                        </td>
                        <td class="block md:table-cell p-2 md:p-3">
                            <span class="block font-bold text-gray-400 md:hidden">Points</span>
                            <span>{{ $row->points }}</span>
                        </td>
                        <td class="block md:table-cell p-2 md:p-3">
                            <span class="block font-bold text-gray-400 md:hidden">Fastest Lap</span>
                            @if($row->fastest_lap_time)
                                <span>{{ $row->fastest_lap_time }} (Rank {{ $row->fastest_lap_rank }})</span>
                            @else
                                <span>—</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</x-app-layout>
