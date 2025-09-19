<x-app-layout>
<style>
    /* Ensure long words/URLs wrap in titles and comments */
    .break-text {
        word-wrap: break-word;      /* older browsers */
        overflow-wrap: break-word;  /* modern browsers */
        white-space: normal;        /* allow wrapping */
    }
</style>

<div class="px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto mt-8 text-white audiowide-regular">
    <h2 class="text-3xl font-bold mb-2 break-text">{{ $post->title }}</h2>
    <p class="text-gray-400 mb-4">
        by {{ $post->user->name }} — {{ $post->created_at->format('d M Y H:i') }}
    </p>
    <div class="bg-gray-800 p-4 rounded mb-4 break-text">
        {{ $post->body }}
    </div>

    {{-- Likes --}}
    @auth
    <form action="{{ route('posts.like', $post->id) }}" method="POST" class="mb-6">
        @csrf
        <button type="submit" class="bg-blue-600 px-3 py-1 rounded hover:bg-blue-500">
            👍 Like ({{ $post->likes->count() }})
        </button>
    </form>
    @else
        <p class="mb-6 text-gray-400">Login to like this post.</p>
    @endauth

    {{-- Comments --}}
    <h3 class="text-xl font-bold mb-3">Comments ({{ $post->comments->count() }})</h3>

    @auth
    <form action="{{ route('posts.comment', $post->id) }}" method="POST" class="mb-6">
        @csrf
        <textarea name="body" rows="3" class="w-full p-2 rounded text-black" placeholder="Write a comment..." required></textarea>
        <button type="submit" class="bg-green-600 px-4 py-2 rounded font-bold hover:bg-green-500 mt-2">
            Add Comment
        </button>
    </form>
    @else
        <p class="mb-6 text-gray-400">Login to comment.</p>
    @endauth

    @forelse($post->comments as $comment)
        <div class="bg-gray-800 p-3 rounded mb-3">
            <p class="text-sm text-gray-300 mb-1">
                {{ $comment->user->name }} — {{ $comment->created_at->diffForHumans() }}
            </p>
            <p class="break-text">{{ $comment->body }}</p>
        </div>
    @empty
        <p class="text-gray-400">No comments yet.</p>
    @endforelse
</div>
</x-app-layout>
