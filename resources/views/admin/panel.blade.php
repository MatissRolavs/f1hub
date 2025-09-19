<x-app-layout>
    <div class="max-w-5xl mx-auto p-6 text-white">
        <h1 class="text-3xl font-bold mb-8">Admin Panel</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Sync Data Info -->
            <a href="{{ route('admin.data') }}"
               class="bg-gray-800 hover:bg-gray-700 transition rounded-lg shadow-lg p-6 flex flex-col items-center text-center group">
                <div class="bg-blue-600 p-4 rounded-full mb-4 group-hover:scale-110 transition">
                    🔄
                </div>
                <h2 class="text-xl font-semibold mb-2">Sync Data Info</h2>
                <p class="text-gray-400 text-sm">View and trigger data synchronization for races, results, and drivers.</p>
            </a>

            <!-- Manage Races -->
            <a href="{{ route('admin.races.index') }}"
               class="bg-gray-800 hover:bg-gray-700 transition rounded-lg shadow-lg p-6 flex flex-col items-center text-center group">
                <div class="bg-green-600 p-4 rounded-full mb-4 group-hover:scale-110 transition">
                    🏁
                </div>
                <h2 class="text-xl font-semibold mb-2">Manage Races</h2>
                <p class="text-gray-400 text-sm">Edit race details, update track info, and manage race data.</p>
            </a>
        </div>
    </div>
</x-app-layout>
