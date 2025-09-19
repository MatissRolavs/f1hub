<x-app-layout>
<style>
    .blurred {
        filter: blur(4px);
        pointer-events: none;
        user-select: none;
    }
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.6);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 50;
    }
    .modal-overlay.active {
        display: flex;
    }
    .modal-content {
        background-color: #1a1a1a;
        padding: 2rem;
        border-radius: 12px;
        width: 100%;
        max-width: 600px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.4);
    }

    /* Post grid */
    .post-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    /* Forum-style post card */
    .post-card {
        background-color: #1a1a1a;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.4);
        color: #fff;
        font-family: monospace;
        padding: 1.5rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        cursor: pointer;

        /* Equal height & spacing */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 180px;
    }
    .post-card:hover {
        transform: translateY(-4px);
        background-color: #222;
        box-shadow: 0 6px 16px rgba(0,0,0,0.6);
    }
    .post-card-header {
        border-bottom: 1px solid rgba(255,255,255,0.1);
        padding-bottom: 0.5rem;
        margin-bottom: 0.5rem;
    }
    .post-card-header h4 {
        font-size: 1.1rem;
        font-weight: bold;
        margin: 0;

        /* Wrapping + ellipsis after 2 lines */
        display: -webkit-box;
        -webkit-line-clamp: 2; /* number of lines before cutting off */
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
        word-break: break-word;
    }
    .post-card-meta {
        font-size: 0.85rem;
        color: #bbb;
    }
    .post-card-footer {
        font-size: 0.85rem;
        color: #999;
        margin-top: auto;
        padding-top: 0.5rem;
        border-top: 1px solid rgba(255,255,255,0.05);
    }
</style>

<div id="forum-container" class="px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto mt-8 text-white audiowide-regular">
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

    <div class="post-grid">
        @forelse($posts as $post)
            <div class="post-card" onclick="window.location.href='{{ route('posts.show', $post->id) }}'">
                <div class="post-card-header">
                    <h4>{{ $post->title }}</h4>
                    <p class="post-card-meta">
                        by {{ $post->user->name }} — {{ $post->created_at->diffForHumans() }}
                    </p>
                </div>
                <div class="post-card-footer">
                    💬 {{ $post->comments_count }} comments
                </div>
            </div>
        @empty
            <p class="text-gray-400">No posts found.</p>
        @endforelse
    </div>
</div>

{{-- Modal --}}
<div id="modal" class="modal-overlay">
    <div class="modal-content">
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
            modal.classList.add('active');
            forumContainer.classList.add('blurred');
        });
    }

    if(closeModalBtn){
        closeModalBtn.addEventListener('click', () => {
            modal.classList.remove('active');
            forumContainer.classList.remove('blurred');
        });
    }

    modal.addEventListener('click', (e) => {
        if(e.target === modal){
            modal.classList.remove('active');
            forumContainer.classList.remove('blurred');
        }
    });
</script>
</x-app-layout>
