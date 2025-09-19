<x-app-layout>
<style>
    .race-card {
        background-color: #1a1a1a;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.4);
        color: #fff;
        font-family: monospace;
        padding: 1.5rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        cursor: pointer;

        /* Make all cards same height */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 180px;
    }
    .race-card:hover {
        transform: translateY(-4px);
        background-color: #222;
        box-shadow: 0 6px 16px rgba(0,0,0,0.6);
    }
    .race-card-header {
        border-bottom: 1px solid rgba(255,255,255,0.1);
        padding-bottom: 0.5rem;
        margin-bottom: 0.5rem;
    }
    .race-card h3 {
        font-size: 1.1rem;
        font-weight: bold;
        margin: 0;
    }
    .race-card-date {
        font-size: 0.85rem;
        color: #bbb;
    }
    .race-card-footer {
        font-size: 0.85rem;
        color: #999;
        margin-top: auto;
        padding-top: 0.5rem;
        border-top: 1px solid rgba(255,255,255,0.05);
    }
</style>

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
            <div onclick="window.location.href='{{ route('forums.show', $race->id) }}'" class="race-card">
                <div class="race-card-header">
                    <h3>{{ $race->name }}</h3>
                    <p class="race-card-date">{{ \Carbon\Carbon::parse($race->date)->format('d M Y') }}</p>
                </div>
                <div class="race-card-footer">
                    💬 Posts: {{ $race->forum_posts_count }}
                </div>
            </div>
        @empty
            <p class="text-gray-400">No races found.</p>
        @endforelse
    </div>
</div>
</x-app-layout>
