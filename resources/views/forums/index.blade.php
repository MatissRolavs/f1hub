<x-app-layout>
<div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto mt-8 text-white audiowide-regular">

    <!-- Title Card -->
<div class="h-52 relative bg-gradient-to-r from-red-700/80 to-black/80 rounded-xl shadow-lg p-8 text-center mb-16">
    <h2 class="text-5xl font-bold audiowide-regular">Race Forums</h2>
    <p class="text-gray-300 mt-2">Discuss races, share predictions, and relive the action</p>

    <!-- Top Stats (offset into the card) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 absolute left-1/2 transform -translate-x-1/2 w-full max-w-4xl -bottom-12 px-4">
        <!-- Left: Red -->
        <div class="bg-gradient-to-br from-red-700 to-red-800 rounded-lg p-6 shadow-md">
            <p class="text-2xl font-bold">{{ number_format($thanksLeft) }}</p>
            <p class="text-sm uppercase tracking-wide">Likes Left</p>
        </div>
        <!-- Middle: Darker Red -->
        <div class="bg-gradient-to-br from-red-800 to-red-900 rounded-lg p-6 shadow-md">
            <p class="text-2xl font-bold">{{ number_format($totalPosts) }}</p>
            <p class="text-sm uppercase tracking-wide">Total Posts</p>
        </div>
        <!-- Right: Black -->
        <div class="bg-gradient-to-br from-red-900 to-black rounded-lg p-6 shadow-md">
            <p class="text-2xl font-bold">{{ number_format($totalRaces) }}</p>
            <p class="text-sm uppercase tracking-wide">Total Forums</p>
        </div>
    </div>
</div>


    <!-- Spacer to account for offset stats -->
    <div class="h-16"></div>

    <!-- Search + Sort -->
    <form method="GET" class="flex flex-col sm:flex-row gap-4 mb-8 justify-center">
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

    <!-- Forum-style Race List -->
    <div class="space-y-4">
        @forelse($races as $race)
            <div onclick="window.location.href='{{ route('forums.show', $race->id) }}'"
                 class="bg-[#1a1a1a] rounded-lg shadow-md p-6 cursor-pointer transition hover:bg-[#222] hover:shadow-lg">
                
                <!-- Race Header -->
                <div class="flex justify-between items-center border-b border-white/10 pb-2 mb-4">
                    <div>
                        <h3 class="text-lg font-bold">{{ $race->name }}</h3>
                        <p class="text-sm text-gray-400">
                            {{ \Carbon\Carbon::parse($race->date)->format('d M Y') }}
                        </p>
                    </div>
                    <span class="text-xs bg-gray-700 px-2 py-1 rounded">
                        Round {{ $race->round ?? '—' }}
                    </span>
                </div>

                <!-- Race Footer -->
                <div class="flex justify-between items-center text-sm text-gray-400">
                    <span>💬 Posts: {{ $race->forum_posts_count }}</span>
                    <span>Last activity: {{ $race->last_post_at ? \Carbon\Carbon::parse($race->last_post_at)->diffForHumans() : '—' }}</span>
                </div>
            </div>
        @empty
            <p class="text-gray-400">No races found.</p>
        @endforelse
    </div>

</div>
</x-app-layout>
