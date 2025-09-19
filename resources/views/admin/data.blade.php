<x-app-layout>
    <div class="max-w-3xl mx-auto p-6 text-white">
        <h1 class="text-3xl font-bold mb-6">Data Sync Panel</h1>

        @if(session('success'))
            <div class="bg-green-600 text-white p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-4">
            <!-- Sync Race Results -->
            <a href="{{ route('results.index') }}"
               class="block text-center bg-blue-600 hover:bg-blue-500 px-6 py-4 rounded-lg font-bold text-lg">
                🔄 Sync Season Race Results
            </a>

            <!-- Sync Current Season Races -->
            <a href="{{ route('races.sync') }}"
               class="block text-center bg-green-600 hover:bg-green-500 px-6 py-4 rounded-lg font-bold text-lg">
                🏁 Sync Current Season Races
            </a>

            <!-- Sync Driver Standings -->
            <a href="{{ route('drivers.sync') }}"
               class="block text-center bg-yellow-500 hover:bg-yellow-400 px-6 py-4 rounded-lg font-bold text-lg text-black">
                🏎️ Sync Driver Standings
            </a>
        </div>
    </div>
</x-app-layout>
