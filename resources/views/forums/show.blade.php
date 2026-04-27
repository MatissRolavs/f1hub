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
    .forum-hero {
        position: relative;
        overflow: hidden;
        background:
            radial-gradient(ellipse at 50% 20%, rgba(225,6,0,0.25) 0%, transparent 55%),
            linear-gradient(135deg, #0a0a0f 0%, #15151e 60%, #0a0a0f 100%);
    }
    .forum-hero::before {
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

    .forum-hero h2 {
        letter-spacing: 3px;
        text-shadow: 0 0 30px rgba(225,6,0,0.35);
    }
    .forum-hero h2 .accent {
        color: #e10600;
        text-shadow: 0 0 20px rgba(225,6,0,0.8);
    }

    /* ── Round badge ─────────────────────────────────────── */
    .round-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.4rem 1rem;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: rgba(255,255,255,0.85);
    }

    /* ── Filter controls ─────────────────────────────────── */
    .filter-input, .filter-select {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.15);
        color: white;
        padding-top: 0.65rem;
        padding-bottom: 0.65rem;
        padding-right: 1rem;
        /* padding-left intentionally omitted — set via pl-10 on element */
        border-radius: 9999px;
        transition: border-color 0.25s ease, box-shadow 0.25s ease;
        width: 100%;
    }
    .filter-select { padding-left: 1rem; }
    .filter-input::placeholder { color: rgba(255,255,255,0.4); }
    .filter-input:focus,
    .filter-select:focus {
        outline: none;
        border-color: rgba(225,6,0,0.9);
        box-shadow: 0 0 15px rgba(225,6,0,0.35);
    }
    .filter-select option { background: #15151e; color: white; }

    /* ── Buttons ─────────────────────────────────────────── */
    .btn-f1 {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.9rem 1.5rem;
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
        justify-content: center;
        gap: 0.5rem;
        padding: 0.9rem 1.5rem;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.25);
        border-radius: 0.5rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: white;
        transition: border-color 0.25s ease, background 0.25s ease;
    }
    .btn-ghost:hover {
        border-color: rgba(255,255,255,0.7);
        background: rgba(255,255,255,0.08);
    }

    /* ── Post row ────────────────────────────────────────── */
    .post-row {
        position: relative;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        cursor: pointer;
        transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
        overflow: hidden;
    }
    .post-row::before {
        content: "";
        position: absolute; top: 0; left: 0; bottom: 0;
        width: 4px;
        background: rgba(225,6,0,0.4);
        transition: background 0.25s ease, box-shadow 0.25s ease;
    }
    .post-row:hover {
        transform: translateX(4px);
        background: rgba(225,6,0,0.08);
        border-color: rgba(225,6,0,0.45);
        box-shadow: 0 0 25px rgba(225,6,0,0.25);
    }
    .post-row:hover::before {
        background: #e10600;
        box-shadow: 0 0 15px rgba(225,6,0,0.8);
    }

    /* ── Sidebar card ────────────────────────────────────── */
    .sidebar-card {
        background: linear-gradient(135deg, #1a1a28 0%, #0f0f15 100%);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 1rem;
        padding: 1.25rem;
    }

    @media (prefers-reduced-motion: reduce) {
        .reveal, .reveal-scale { opacity: 1 !important; transform: none !important; transition: none !important; }
    }
</style>

{{-- ───────────────────────── HERO ───────────────────────── --}}
<section class="forum-hero text-white">
    <div class="hero-stripe top"></div>
    <div class="hero-stripe bottom"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 py-14 text-center">
        <div class="reveal-scale">
            <div class="flex justify-center gap-3 mb-4 flex-wrap">
                <span class="round-badge">{{ $race->season }} Season</span>
                @if($race->round)
                    <span class="round-badge">Round {{ $race->round }}</span>
                @endif
            </div>

            <h2 class="audiowide-regular text-3xl md:text-5xl lg:text-6xl font-bold mb-3">
                {{ $race->name }} <span class="accent">Forum</span>
            </h2>

            @if($race->circuit_name || $race->locality)
                <p class="text-gray-300 text-base md:text-lg">
                    {{ $race->circuit_name }}
                    @if($race->locality) — {{ $race->locality }}@if($race->country), {{ $race->country }}@endif @endif
                </p>
            @endif
        </div>
    </div>
</section>

<div id="forum-container" class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto py-12 text-white audiowide-regular transition-all duration-200">

    @if(session('success'))
        <div class="bg-green-600/20 border border-green-500/40 text-green-200 p-4 rounded-lg mb-8 text-center font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-8">

        {{-- ───────────────────────── SIDEBAR ───────────────────────── --}}
        <aside class="w-full lg:w-1/3 space-y-6 reveal">
            @auth
                <button id="openModalBtn" class="btn-f1 w-full">
                    + Create Post
                </button>
            @else
                <div class="sidebar-card text-center">
                    <p class="text-gray-400 mb-4">You must be logged in to create a post.</p>
                    <a href="{{ route('login') }}" class="btn-ghost w-full">Log In</a>
                </div>
            @endauth

            <div class="sidebar-card">
                <h3 class="text-xs text-white/60 tracking-[3px] uppercase mb-3">Filter Posts</h3>
                <form method="GET" class="space-y-3">
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z"/>
                        </svg>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search posts..."
                               class="filter-input pl-10">
                    </div>
                    <select name="sort" class="filter-select">
                        <option value="latest" {{ ($sort ?? '') == 'latest' ? 'selected' : '' }}>Latest Posts</option>
                        <option value="oldest" {{ ($sort ?? '') == 'oldest' ? 'selected' : '' }}>Oldest Posts</option>
                        <option value="most_comments" {{ ($sort ?? '') == 'most_comments' ? 'selected' : '' }}>Most Comments</option>
                        <option value="least_comments" {{ ($sort ?? '') == 'least_comments' ? 'selected' : '' }}>Least Comments</option>
                    </select>
                    <button type="submit" class="btn-ghost w-full">Apply</button>
                </form>
            </div>

            <a href="{{ route('forums.index') }}" class="btn-ghost w-full">
                ← All Forums
            </a>
        </aside>

        {{-- ───────────────────────── POSTS ───────────────────────── --}}
        <main class="flex-1">
            <h3 class="section-title text-xl md:text-2xl font-bold uppercase mb-6 reveal">
                Discussion
            </h3>

            <div class="space-y-3">
                @forelse($posts as $post)
                    <div onclick="window.location.href='{{ route('posts.show', $post->id) }}'"
                         class="post-row reveal">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-base md:text-lg font-bold text-white truncate">{{ $post->title }}</h4>
                                <p class="text-xs text-white/50 mt-1">
                                    by <x-user-badge :user="$post->user" class="font-semibold text-white/80" /> • {{ $post->created_at->diffForHumans() }}
                                </p>
                            </div>

                            <div class="hidden sm:flex items-center gap-5 text-sm text-white/60 shrink-0">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    <span><span class="text-white font-semibold">{{ $post->comments_count }}</span> replies</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-base leading-none">❤</span>
                                    <span><span class="text-white font-semibold">{{ $post->likes_count ?? 0 }}</span> likes</span>
                                </div>
                            </div>

                            <svg class="w-5 h-5 text-white/30 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>

                        {{-- mobile stats --}}
                        <div class="flex sm:hidden items-center gap-4 mt-3 pt-3 border-t border-white/10 text-xs text-white/50">
                            <span>💬 {{ $post->comments_count }} replies</span>
                            <span>❤ {{ $post->likes_count ?? 0 }} likes</span>
                        </div>
                    </div>
                @empty
                    <div class="bg-white/5 border border-white/10 rounded-xl p-12 text-center">
                        <p class="text-gray-400">No posts yet. Be the first to start a discussion!</p>
                    </div>
                @endforelse
            </div>
        </main>
    </div>
</div>

{{-- ───────────────────────── MODAL ───────────────────────── --}}
<div id="modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden justify-center items-center z-50">
    <div class="bg-gradient-to-br from-[#1a1a28] to-[#0f0f15] border border-white/10 p-8 rounded-2xl w-full max-w-xl shadow-2xl mx-4 relative">
        <button type="button" id="closeModalBtn" class="absolute top-3 right-4 text-3xl text-white/60 hover:text-white transition">&times;</button>

        <h3 class="audiowide-regular text-2xl font-bold mb-1 text-white">Create a New Post</h3>
        <p class="text-sm text-white/50 mb-6">{{ $race->name }} — {{ $race->season }}</p>

        <form action="{{ route('forums.store', $race->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block mb-2 text-xs uppercase tracking-widest text-white/60">Title</label>
                <input type="text" name="title" class="filter-input" required>
            </div>
            <div class="mb-6">
                <label class="block mb-2 text-xs uppercase tracking-widest text-white/60">Body</label>
                <textarea name="body" rows="5"
                          class="w-full p-3 rounded-lg bg-white/5 border border-white/15 text-white focus:outline-none focus:border-red-500 focus:shadow-[0_0_15px_rgba(225,6,0,0.35)] transition"
                          required></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" id="closeModalBtnFooter" class="btn-ghost">Cancel</button>
                <button type="submit" class="btn-f1">Post</button>
            </div>
        </form>
    </div>
</div>

<script>
(function(){
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.getElementById('closeModalBtn');
    const closeBtnFooter = document.getElementById('closeModalBtnFooter');
    const modal = document.getElementById('modal');
    const container = document.getElementById('forum-container');

    function open() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        container.classList.add('blur-sm', 'pointer-events-none', 'select-none');
        document.body.style.overflow = 'hidden';
    }
    function close() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        container.classList.remove('blur-sm', 'pointer-events-none', 'select-none');
        document.body.style.overflow = '';
    }

    if (openBtn)        openBtn.addEventListener('click', open);
    if (closeBtn)       closeBtn.addEventListener('click', close);
    if (closeBtnFooter) closeBtnFooter.addEventListener('click', close);

    modal.addEventListener('click', (e) => { if (e.target === modal) close(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !modal.classList.contains('hidden')) close(); });
})();

// Scroll reveal
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
