<x-app-layout>
<div class="px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto mt-8 text-white audiowide-regular">

    <!-- Post Header with Gradient Border -->
    <div class="bg-gradient-to-r from-red-700 to-black p-[2px] rounded-xl shadow-lg">
        <div class="flex items-center gap-6 bg-[#1a1a1a] rounded-xl p-6">
            
            <!-- Calendar Date Box -->
            <div class="flex flex-col items-center justify-center w-20 h-20 rounded-lg shadow-md bg-gradient-to-b from-red-700 to-black">
                <span class="text-lg font-bold">{{ $post->created_at->format('d') }}</span>
                <span class="text-xs uppercase tracking-wide">{{ $post->created_at->format('M') }}</span>
                <span class="text-[10px] text-gray-300">{{ $post->created_at->format('Y') }}</span>
            </div>

            <!-- Title + Author -->
            <div class="flex-1 min-w-0"> <!-- min-w-0 allows flex child to shrink -->
                <h2 class="text-3xl font-bold mb-2 break-words whitespace-normal">
                    {{ $post->title }}
                </h2>
                <p class="text-gray-400 text-sm">
                    by <span class="font-semibold">{{ $post->user->name }}</span> • {{ $post->created_at->format('H:i') }}
                </p>
            </div>
        </div>
    </div>


    <!-- Post Body with Gradient Border -->
    <div class="bg-gradient-to-r from-red-700 to-black p-[2px] rounded-xl shadow-lg mb-6">
        <div class="bg-[#1a1a1a] p-6 rounded-lg break-words whitespace-pre-line leading-relaxed">
            {{ $post->body }}
        </div>
    </div>

    <!-- Comments Section -->
    <div class="bg-[#1a1a1a] rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Comments ({{ $post->comments->count() }})</h3>

            <!-- Like Button -->
            @auth
                <form action="{{ route('posts.like', $post->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-bold border border-red-700 text-white transition
                            {{ $post->isLikedBy(auth()->user()) 
                                    ? 'bg-gradient-to-r from-red-700 to-black' 
                                    : 'bg-transparent hover:bg-gradient-to-r hover:from-red-700 hover:to-black' }}">
                        👍 <span class="text-sm font-normal">({{ $post->likes->count() }})</span>
                    </button>
                </form>
            @else
                <p class="text-gray-400 text-sm">Login to like</p>
            @endauth
        </div>

        @auth
            <form action="{{ route('posts.comment', $post->id) }}" method="POST" class="mb-6">
                @csrf
                <textarea name="body" rows="3" 
                          class="w-full p-3 rounded text-black focus:ring-2 focus:ring-red-600" 
                          placeholder="Write a comment..." required></textarea>
                <button type="submit" 
                        class="mt-2 bg-green-600 px-4 py-2 rounded-lg font-bold hover:bg-green-500 transition">
                    ➕ Add Comment
                </button>
            </form>
        @else
            <p class="text-gray-400 mb-6">Login to comment.</p>
        @endauth

        <!-- Comment List -->
        <div class="space-y-4">
            @forelse($post->comments as $comment)
                <div class="bg-gray-800 p-4 rounded-lg">
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-sm text-gray-300">
                            <span class="font-semibold">{{ $comment->user->name }}</span> 
                            • {{ $comment->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <p class="text-gray-100 break-words whitespace-pre-line">{{ $comment->body }}</p>
                </div>
            @empty
                <p class="text-gray-400">No comments yet.</p>
            @endforelse
        </div>
    </div>
</div>
</x-app-layout>
