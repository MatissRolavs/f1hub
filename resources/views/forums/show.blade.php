<x-app-layout>
<div id="forum-container" class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto mt-8 text-white audiowide-regular transition-all duration-200">

    <!-- Forum Header -->
    <h2 class="text-3xl font-bold mb-6 border-b border-white/10 pb-2">
        {{ $race->name }} — {{ $race->season }} Forum
    </h2>

    <!-- Flash Message -->
    @if(session('success'))
        <div class="bg-green-600 text-white p-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Layout: Sidebar + Main -->
    <div class="flex flex-col lg:flex-row gap-8">

        <!-- Sidebar (wider) -->
        <aside class="w-full lg:w-1/3 space-y-6">
            @auth
                <button id="openModalBtn" 
                        class="w-full bg-gradient-to-r from-red-600 to-red-700 px-4 py-2 rounded-lg font-bold hover:from-red-700 hover:to-red-800 transition">
                    + Create Post
                </button>
            @else
                <p class="text-gray-400">You must be logged in to create a post.</p>
            @endauth

            <!-- Search + Sort -->
            <form method="GET" class="space-y-3 bg-[#1a1a1a] p-4 rounded-lg shadow">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search posts..."
                       class="w-full p-2 rounded text-black">
                <select name="sort" class="w-full p-2 rounded text-black">
                    <option value="latest" {{ ($sort ?? '') == 'latest' ? 'selected' : '' }}>Latest Posts</option>
                    <option value="oldest" {{ ($sort ?? '') == 'oldest' ? 'selected' : '' }}>Oldest Posts</option>
                    <option value="most_comments" {{ ($sort ?? '') == 'most_comments' ? 'selected' : '' }}>Most Comments</option>
                    <option value="least_comments" {{ ($sort ?? '') == 'least_comments' ? 'selected' : '' }}>Least Comments</option>
                </select>
                <button type="submit" class="w-full bg-gray-900 px-4 py-2 rounded font-bold hover:bg-gray-700">
                    Apply
                </button>
            </form>
        </aside>

        <!-- Main Thread List (constrained width) -->
        <main class="flex-1">
            <div class="max-w-4xl mx-auto">
                <div class="divide-y divide-white/10 border border-white/10 rounded-lg overflow-hidden">
                    @forelse($posts as $post)
                        <div onclick="window.location.href='{{ route('posts.show', $post->id) }}'"
                             class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-4 py-4 bg-[#1a1a1a] hover:bg-[#222] cursor-pointer transition">
                            
                            <!-- Left: Title + Meta -->
                            <div class="flex-1 min-w-0"> <!-- min-w-0 ensures flex truncation works -->
                                <h4 class="text-lg font-bold truncate mb-1">{{ $post->title }}</h4>
                                <p class="text-sm text-gray-400">
                                    by <span class="font-semibold">{{ $post->user->name }}</span> • {{ $post->created_at->diffForHumans() }}
                                </p>
                            </div>


                            <!-- Right: Stats -->
                            <div class="mt-2 sm:mt-0 flex items-center gap-6 text-sm text-gray-400">
                                <span class="whitespace-nowrap">💬 {{ $post->comments_count }} replies</span>
                                <span class="whitespace-nowrap">👍 {{ $post->likes_count ?? 0 }} likes</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 p-4">No posts found.</p>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal -->
<div id="modal" class="fixed inset-0 bg-black/60 hidden justify-center items-center z-50">
    <div class="bg-[#1a1a1a] p-8 rounded-lg w-full max-w-xl shadow-xl">
        <h3 class="text-xl font-bold mb-4">Create a New Post</h3>
        <form action="{{ route('forums.store', $race->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="block mb-1">Title</label>
                <input type="text" name="title" class="w-full p-2 rounded text-black" required>
            </div>
            <div class="mb-3">
                <label class="block mb-1">Body</label>
                <textarea name="body" rows="4" class="w-full p-2 rounded text-black" required></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" id="closeModalBtn" class="bg-gray-500 px-4 py-2 rounded hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit" class="bg-green-600 px-4 py-2 rounded font-bold hover:bg-green-500">
                    Post
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const openModalBtn = document.getElementById('openModalBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const modal = document.getElementById('modal');
    const forumContainer = document.getElementById('forum-container');

    if(openModalBtn){
        openModalBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            forumContainer.classList.add('blur-sm', 'pointer-events-none', 'select-none');
        });
    }

    if(closeModalBtn){
        closeModalBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            forumContainer.classList.remove('blur-sm', 'pointer-events-none', 'select-none');
        });
    }

    modal.addEventListener('click', (e) => {
        if(e.target === modal){
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            forumContainer.classList.remove('blur-sm', 'pointer-events-none', 'select-none');
        }
    });
</script>
</x-app-layout>
