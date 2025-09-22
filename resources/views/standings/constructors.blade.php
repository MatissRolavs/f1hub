<x-app-layout>
<div class="px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto mt-8">
    <div class="bg-[#1a1a1a] rounded-xl shadow-lg text-white font-mono p-8">
        <h2 class="text-2xl font-bold mb-4 text-center audiowide-regular">
            {{ $season }} Constructors Standings
        </h2>

        <table class="w-full border-collapse md:table block">
            <thead class="hidden md:table-header-group">
                <tr>
                    <th class="bg-[#222] text-gray-400 uppercase text-sm p-3 text-left">Pos</th>
                    <th class="bg-[#222] text-gray-400 uppercase text-sm p-3 text-left">Constructor</th>
                    <th class="bg-[#222] text-gray-400 uppercase text-sm p-3 text-left">Nationality</th>
                    <th class="bg-[#222] text-gray-400 uppercase text-sm p-3 text-left">Points</th>
                    <th class="bg-[#222] text-gray-400 uppercase text-sm p-3 text-left">Wins</th>
                </tr>
            </thead>
            <tbody class="block md:table-row-group">
                @foreach($standings as $index => $row)
                    <tr class="block md:table-row mb-4 md:mb-0 even:bg-[#2a2a2a] hover:bg-[#333] rounded-lg md:rounded-none p-3 md:p-0">
                        <td class="block md:table-cell p-2 md:p-3">
                            <span class="block font-bold text-gray-400 md:hidden">Pos</span>
                            <span>{{ $index + 1 }}</span>
                        </td>
                        <td class="block md:table-cell p-2 md:p-3">
                            <span class="block font-bold text-gray-400 md:hidden">Constructor</span>
                            <span class="font-bold">{{ $row->constructor_name }}</span>
                        </td>
                        <td class="block md:table-cell p-2 md:p-3">
                            <span class="block font-bold text-gray-400 md:hidden">Nationality</span>
                            <span>{{ $row->constructor_nationality }}</span>
                        </td>
                        <td class="block md:table-cell p-2 md:p-3">
                            <span class="block font-bold text-gray-400 md:hidden">Points</span>
                            <span>{{ $row->points }}</span>
                        </td>
                        <td class="block md:table-cell p-2 md:p-3">
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
