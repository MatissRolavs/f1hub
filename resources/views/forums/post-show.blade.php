<x-app-layout>
<style>
    /* ── Scroll reveal ───────────────────────────────────── */
    .reveal, .reveal-scale {
        opacity: 0;
        transition: opacity 0.9s cubic-bezier(.2,.65,.3,1), transform 0.9s cubic-bezier(.2,.65,.3,1);
        will-change: opacity, transform;
    }
    .reveal       { transform: translateY(40px); }
    .reveal-scale { transform: scale(0.96); }
    .is-visible   { opacity: 1 !important; transform: none !important; }

    /* ── Section title ───────────────────────────────────── */
    .section-title {
        position: relative;
        padding-left: 1.25rem;
        display: inline-block;
    }
    .section-title::before {
        content: "";
        position: absolute;
        left: 0; top: 8%; bottom: 8%;
        width: 6px;
        background: #e10600;
        box-shadow: 0 0 12px rgba(225,6,0,0.6);
    }

    /* ── Hero ─────────────────────────────────────────────── */
    .post-hero {
        position: relative;
        overflow: hidden;
        background:
            radial-gradient(ellipse at 50% 20%, rgba(225,6,0,0.25) 0%, transparent 55%),
            linear-gradient(135deg, #0a0a0f 0%, #15151e 60%, #0a0a0f 100%);
    }
    .post-hero::before {
        content: "";
        position: absolute; inset: 0;
        background-image: repeating-linear-gradient(
            45deg,
            rgba(255,255,255,0.02) 0 14px,
            transparent 14px 28px
        );
        pointer-events: none;
    }
    .hero-stripe {
        position: absolute; left: 0;
        height: 4px; width: 100%;
        background: linear-gradient(90deg, #e10600 0%, #e10600 55%, transparent 100%);
        box-shadow: 0 0 20px rgba(225,6,0,0.8);
        z-index: 3;
    }
    .hero-stripe.top    { top: 0; }
    .hero-stripe.bottom { bottom: 0; background: linear-gradient(270deg, #e10600 0%, #e10600 55%, transparent 100%); }

    /* ── Calendar date block ─────────────────────────────── */
    .date-block {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 5.5rem; height: 5.5rem;
        border-radius: 1rem;
        background: linear-gradient(180deg, #e10600 0%, #7a0300 100%);
        color: white;
        box-shadow: 0 4px 20px rgba(225,6,0,0.35);
        flex-shrink: 0;
    }
    .date-block .d { font-family: "Audiowide", sans-serif; font-size: 1.75rem; font-weight: 800; line-height: 1; }
    .date-block .m { font-size: 0.7rem; letter-spacing: 2px; text-transform: uppercase; margin-top: 0.25rem; }
    .date-block .y { font-size: 0.65rem; color: rgba(255,255,255,0.7); margin-top: 0.1rem; }

    /* ── Body card ───────────────────────────────────────── */
    .body-card {
        position: relative;
        background: linear-gradient(135deg, #1a1a28 0%, #0f0f15 100%);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 1rem;
        padding: 2rem;
        overflow: hidden;
    }
    .body-card::before {
        content: "";
        position: absolute; top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, #e10600 0%, #e10600 55%, transparent 100%);
        box-shadow: 0 0 15px rgba(225,6,0,0.6);
    }

    /* ── Like button ─────────────────────────────────────── */
    .like-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.25rem;
        border-radius: 9999px;
        font-weight: 700;
        letter-spacing: 1px;
        transition: transform 0.2s ease, box-shadow 0.25s ease, background 0.25s ease, border-color 0.25s ease;
        border: 1px solid rgba(225,6,0,0.7);
        color: white;
    }
    .like-btn:not(.liked) {
        background: rgba(255,255,255,0.02);
    }
    .like-btn:not(.liked):hover {
        background: rgba(225,6,0,0.15);
        border-color: rgba(225,6,0,1);
        box-shadow: 0 0 20px rgba(225,6,0,0.4);
    }
    .like-btn.liked {
        background: linear-gradient(90deg, #e10600 0%, #a30400 100%);
        box-shadow: 0 0 20px rgba(225,6,0,0.5);
    }
    .like-btn.liked:hover { transform: translateY(-1px); }

    /* ── Comment bubble ──────────────────────────────────── */
    .comment-bubble {
        position: relative;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 1rem;
        padding: 1rem 1.25rem;
        transition: border-color 0.25s ease, background 0.25s ease;
    }
    .comment-bubble::before {
        content: "";
        position: absolute; top: 0.75rem; left: 0; bottom: 0.75rem;
        width: 3px;
        background: rgba(225,6,0,0.3);
        border-radius: 2px;
        transition: background 0.25s ease;
    }
    .comment-bubble:hover {
        background: rgba(225,6,0,0.05);
        border-color: rgba(225,6,0,0.25);
    }
    .comment-bubble:hover::before { background: #e10600; }

    /* ── Form inputs ─────────────────────────────────────── */
    .comment-textarea {
        width: 100%;
        padding: 0.9rem 1rem;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.15);
        color: white;
        border-radius: 0.75rem;
        resize: vertical;
        transition: border-color 0.25s ease, box-shadow 0.25s ease;
    }
    .comment-textarea::placeholder { color: rgba(255,255,255,0.4); }
    .comment-textarea:focus {
        outline: none;
        border-color: rgba(225,6,0,0.9);
        box-shadow: 0 0 15px rgba(225,6,0,0.35);
    }

    /* ── Buttons ─────────────────────────────────────────── */
    .btn-f1 {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.85rem 1.5rem;
        background: linear-gradient(90deg, #e10600 0%, #a30400 100%);
        border-radius: 0.5rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: white;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .btn-f1:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(225,6,0,0.55);
    }
    .btn-ghost {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.55rem 1rem;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 9999px;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 1px;
        color: rgba(255,255,255,0.75);
        transition: border-color 0.25s ease, background 0.25s ease, color 0.25s ease;
    }
    .btn-ghost:hover {
        border-color: rgba(255,255,255,0.6);
        background: rgba(255,255,255,0.08);
        color: white;
    }

    @media (prefers-reduced-motion: reduce) {
        .reveal, .reveal-scale { opacity: 1 !important; transform: none !important; transition: none !important; }
    }
</style>

{{-- ───────────────────────── HERO ───────────────────────── --}}
<section class="post-hero text-white">
    <div class="hero-stripe top"></div>
    <div class="hero-stripe bottom"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 py-12">
        <div class="reveal-scale">
            {{-- Breadcrumb --}}
            @if($post->race)
                <div class="flex items-center gap-2 text-sm text-white/50 audiowide-regular mb-6 flex-wrap">
                    <a href="{{ route('forums.index') }}" class="hover:text-white transition">Forums</a>
                    <span>›</span>
                    <a href="{{ route('forums.show', $post->race->id) }}" class="hover:text-white transition truncate max-w-xs">
                        {{ $post->race->name }}
                    </a>
                    <span>›</span>
                    <span class="text-white/30">Post</span>
                </div>
            @endif

            <div class="flex items-start gap-5">
                {{-- Date block --}}
                <div class="date-block">
                    <span class="d">{{ $post->created_at->format('d') }}</span>
                    <span class="m">{{ $post->created_at->format('M') }}</span>
                    <span class="y">{{ $post->created_at->format('Y') }}</span>
                </div>

                <div class="flex-1 min-w-0">
                    <h2 class="audiowide-regular text-2xl md:text-4xl font-bold text-white break-words mb-3">
                        {{ $post->title }}
                    </h2>
                    <p class="text-sm text-white/60 audiowide-regular">
                        by <x-user-badge :user="$post->user" class="font-semibold text-white/90" />
                        <span class="mx-1">•</span>
                        {{ $post->created_at->format('H:i') }}
                        <span class="mx-1">•</span>
                        {{ $post->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-white audiowide-regular">

    {{-- ───────────────────────── BODY ───────────────────────── --}}
    <div class="body-card reveal mb-8">
        <div class="break-words whitespace-pre-line leading-relaxed text-white/90">
            {{ $post->body }}
        </div>
    </div>

    {{-- ───────────────────────── COMMENTS ───────────────────────── --}}
    <div class="flex items-center justify-between mb-6 reveal gap-4">
        <h3 class="section-title text-xl md:text-2xl font-bold uppercase">
            Comments ({{ $post->comments->count() }})
        </h3>

        @auth
            <form action="{{ route('posts.like', $post->id) }}" method="POST">
                @csrf
                @php($liked = $post->isLikedBy(auth()->user()))
                <button type="submit" class="like-btn {{ $liked ? 'liked' : '' }}">
                    <span>{{ $liked ? '❤' : '🤍' }}</span>
                    <span>{{ $post->likes->count() }}</span>
                    <span class="uppercase text-xs tracking-widest">{{ $liked ? 'Liked' : 'Like' }}</span>
                </button>
            </form>
        @else
            <span class="text-sm text-white/50">Log in to like</span>
        @endauth
    </div>

    {{-- New comment form --}}
    @auth
        <form action="{{ route('posts.comment', $post->id) }}" method="POST" class="mb-8 reveal">
            @csrf
            <label class="block mb-2 text-xs uppercase tracking-widest text-white/60">Write a comment</label>
            <textarea name="body" rows="3"
                      class="comment-textarea"
                      placeholder="Share your thoughts..." required></textarea>
            <div class="flex justify-end mt-3">
                <button type="submit" class="btn-f1">
                    Add Comment
                </button>
            </div>
        </form>
    @else
        <div class="bg-white/5 border border-white/10 rounded-xl p-6 text-center mb-8">
            <p class="text-gray-400">
                <a href="{{ route('login') }}" class="text-red-400 hover:text-red-300 underline">Log in</a> to join the discussion.
            </p>
        </div>
    @endauth

    {{-- Comment list --}}
    <div class="space-y-3">
        @forelse($post->comments as $comment)
            <div class="comment-bubble reveal">
                <div class="flex items-center justify-between mb-2 gap-2 flex-wrap">
                    <p class="text-sm flex items-center gap-2">
                        <x-user-badge :user="$comment->user" class="font-semibold text-white" />
                    </p>
                    <p class="text-xs text-white/50 tracking-widest uppercase">
                        {{ $comment->created_at->diffForHumans() }}
                    </p>
                </div>
                <p class="text-white/90 break-words whitespace-pre-line leading-relaxed">{{ $comment->body }}</p>
            </div>
        @empty
            <div class="bg-white/5 border border-white/10 rounded-xl p-8 text-center">
                <p class="text-gray-400">No comments yet. Start the discussion!</p>
            </div>
        @endforelse
    </div>

    @if($post->race)
        <div class="mt-12 text-center">
            <a href="{{ route('forums.show', $post->race->id) }}" class="btn-ghost">
                ← Back to {{ $post->race->name }}
            </a>
        </div>
    @endif
</div>

<script>
(function(){
    const targets = document.querySelectorAll('.reveal, .reveal-scale');
    if (!('IntersectionObserver' in window) || !targets.length) {
        targets.forEach(el => el.classList.add('is-visible'));
        return;
    }
    const io = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                io.unobserve(entry.target);
            }
        });
    }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
    targets.forEach(el => io.observe(el));
})();
</script>
</x-app-layout>
