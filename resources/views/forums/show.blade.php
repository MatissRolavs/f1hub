<x-app-layout>
<div id="forum-container" class="px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto mt-8 text-white audiowide-regular transition-all duration-200">
    <h2 class="text-2xl font-bold mb-4">
        {{ $race->name }} — {{ $race->season }} Forum
    </h2>

    @if(session('success'))
        <div class="bg-green-600 text-white p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @auth
        <button id="openModalBtn" class="bg-gray-900 px-4 py-2 rounded font-bold hover:bg-gray-700 mb-6">
            + Create Post
        </button>
    @else
        <p class="mb-6 text-gray-400">You must be logged in to create a post.</p>
    @endauth

    {{-- Search + Sort --}}
    <form method="GET" class="flex flex-col sm:flex-row gap-4 mb-6">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search posts..."
               class="p-2 rounded text-black w-full sm:w-1/3">
        <select name="sort" class="p-2 rounded text-black">
            <option value="latest" {{ ($sort ?? '') == 'latest' ? 'selected' : '' }}>Latest Posts</option>
            <option value="oldest" {{ ($sort ?? '') == 'oldest' ? 'selected' : '' }}>Oldest Posts</option>
            <option value="most_comments" {{ ($sort ?? '') == 'most_comments' ? 'selected' : '' }}>Most Comments</option>
            <option value="least_comments" {{ ($sort ?? '') == 'least_comments' ? 'selected' : '' }}>Least Comments</option>
        </select>
        <button type="submit" class="bg-gray-900 px-4 py-2 rounded font-bold hover:bg-gray-700">
            Apply
        </button>
    </form>

    {{-- Post Grid --}}
    <div class="grid grid-cols-[repeat(auto-fill,minmax(280px,1fr))] gap-6">
        @forelse($posts as $post)
            <div onclick="window.location.href='{{ route('posts.show', $post->id) }}'"
                 class="bg-[#1a1a1a] rounded-lg overflow-hidden shadow-lg text-white font-mono p-6 flex flex-col justify-between min-h-[180px] cursor-pointer transition-all duration-200 ease-in-out hover:-translate-y-1 hover:bg-[#222] hover:shadow-xl">
                
                {{-- Header --}}
                <div class="border-b border-white/10 pb-2 mb-2">
                    <h4 class="text-lg font-bold m-0 line-clamp-2 break-words">{{ $post->title }}</h4>
                    <p class="text-sm text-gray-400">
                        by {{ $post->user->name }} — {{ $post->created_at->diffForHumans() }}
                    </p>
                </div>

                {{-- Footer --}}
                <div class="text-sm text-gray-400 mt-auto pt-2 border-t border-white/5">
                    💬 {{ $post->comments_count }} comments
                </div>
            </div>
        @empty
            <p class="text-gray-400">No posts found.</p>
        @endforelse
    </div>
</div>

{{-- Modal --}}
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
