<x-app-layout>
<div class="px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto mt-8">
    <div class="bg-[#1a1a1a] rounded-xl shadow-lg text-white font-mono p-8">
        <h2 class="text-2xl font-bold mb-4 text-center audiowide-regular">
            {{ $season }} Driver Standings
        </h2>
        <a href="{{ route('standings.constructors', ['season' => $season]) }}"
           class="text-lg font-bold mb-4 text-blue-500 underline hover:text-blue-400 block text-center audiowide-regular">
            Press To See {{ $season }} Constructors Standings
        </a>

        <table class="w-full border-collapse md:table block">
    <thead class="hidden md:table-header-group">
        <tr>
            <th class="bg-[#222] text-gray-400 uppercase text-sm p-3 text-left">Pos</th>
            <th class="bg-[#222] text-gray-400 uppercase text-sm p-3 text-left">Driver</th>
            <th class="bg-[#222] text-gray-400 uppercase text-sm p-3 text-left">Nationality</th>
            <th class="bg-[#222] text-gray-400 uppercase text-sm p-3 text-left">Constructor</th>
            <th class="bg-[#222] text-gray-400 uppercase text-sm p-3 text-left">Points</th>
            <th class="bg-[#222] text-gray-400 uppercase text-sm p-3 text-left">Wins</th>
        </tr>
    </thead>
    <tbody class="block md:table-row-group">
        @foreach($standings as $row)
            <tr class="block md:table-row mb-4 md:mb-0 even:bg-[#2a2a2a] hover:bg-[#333] rounded-lg md:rounded-none p-3 md:p-0">
                <td data-label="Pos" class="block md:table-cell p-2 md:p-3">
                    <span class="block font-bold text-gray-400 md:hidden">Pos</span>
                    <span>{{ $row->position }}</span>
                </td>
                <td data-label="Driver" class="block md:table-cell p-2 md:p-3">
                    <span class="block font-bold text-gray-400 md:hidden">Driver</span>
                    <a href="{{ route('drivers.show', $row->id) }}">
                        <span class="font-bold">{{ $row->given_name }} {{ $row->family_name }}</span>
                    </a>
                    @if($row->code)
                        <span class="text-gray-400 text-sm">({{ $row->code }})</span>
                    @endif
                </td>
                <td data-label="Nationality" class="block md:table-cell p-2 md:p-3">
                    <span class="block font-bold text-gray-400 md:hidden">Nationality</span>
                    <span>{{ $row->driver_nationality }}</span>
                </td>
                <td data-label="Constructor" class="block md:table-cell p-2 md:p-3">
                    <span class="block font-bold text-gray-400 md:hidden">Constructor</span>
                    <span>{{ $row->constructor_name }}</span>
                </td>
                <td data-label="Points" class="block md:table-cell p-2 md:p-3">
                    <span class="block font-bold text-gray-400 md:hidden">Points</span>
                    <span>{{ $row->points }}</span>
                </td>
                <td data-label="Wins" class="block md:table-cell p-2 md:p-3">
                    <span class="block font-bold text-gray-400 md:hidden">Wins</span>
                    <span>{{ $row->wins }}</span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
    </div>
</div>
</x-app-layout>
