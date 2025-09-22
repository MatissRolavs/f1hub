<x-app-layout>
<div class="px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto mt-8 text-white audiowide-regular">
    <h2 class="text-2xl font-bold mb-6 text-center">🏁 Race Forums</h2>

    {{-- Search + Sort Form --}}
    <form method="GET" class="flex flex-col sm:flex-row gap-4 mb-6 justify-center">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search races..."
               class="p-2 rounded text-black w-full sm:w-1/3">

        <select name="sort" class="p-2 rounded text-black">
            <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>Latest Race</option>
            <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>Oldest Race</option>
            <option value="most_posts" {{ $sort == 'most_posts' ? 'selected' : '' }}>Race with Most Posts</option>
            <option value="least_posts" {{ $sort == 'least_posts' ? 'selected' : '' }}>Race with Least Posts</option>
        </select>

        <button type="submit" class="bg-gray-900 px-4 py-2 rounded font-bold hover:bg-gray-700">
            Apply
        </button>
    </form>

    {{-- Race Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($races as $race)
            <div onclick="window.location.href='{{ route('forums.show', $race->id) }}'"
                 class="bg-[#1a1a1a] rounded-lg overflow-hidden shadow-lg text-white font-mono p-6 flex flex-col justify-between min-h-[180px] cursor-pointer transition-all duration-200 ease-in-out hover:-translate-y-1 hover:bg-[#222] hover:shadow-xl">
                
                {{-- Header --}}
                <div class="border-b border-white/10 pb-2 mb-2">
                    <h3 class="text-lg font-bold m-0">{{ $race->name }}</h3>
                    <p class="text-sm text-gray-400">{{ \Carbon\Carbon::parse($race->date)->format('d M Y') }}</p>
                </div>

                {{-- Footer --}}
                <div class="text-sm text-gray-400 mt-auto pt-2 border-t border-white/5">
                    💬 Posts: {{ $race->forum_posts_count }}
                </div>
            </div>
        @empty
            <p class="text-gray-400">No races found.</p>
        @endforelse
    </div>
</div>
</x-app-layout>
