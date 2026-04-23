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

    .reveal-stagger > * {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.7s cubic-bezier(.2,.65,.3,1), transform 0.7s cubic-bezier(.2,.65,.3,1);
    }
    .reveal-stagger.is-visible > * { opacity: 1; transform: none; }
    .reveal-stagger.is-visible > *:nth-child(1)  { transition-delay: 0.00s; }
    .reveal-stagger.is-visible > *:nth-child(2)  { transition-delay: 0.08s; }
    .reveal-stagger.is-visible > *:nth-child(3)  { transition-delay: 0.16s; }
    .reveal-stagger.is-visible > *:nth-child(4)  { transition-delay: 0.24s; }
    .reveal-stagger.is-visible > *:nth-child(5)  { transition-delay: 0.32s; }
    .reveal-stagger.is-visible > *:nth-child(n+6){ transition-delay: 0.40s; }

    /* ── Section title with red accent ───────────────────── */
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
    .forums-hero {
        position: relative;
        overflow: hidden;
        background:
            radial-gradient(ellipse at 50% 20%, rgba(225,6,0,0.25) 0%, transparent 55%),
            linear-gradient(135deg, #0a0a0f 0%, #15151e 60%, #0a0a0f 100%);
    }
    .forums-hero::before {
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

    .forums-hero h2 {
        letter-spacing: 4px;
        text-shadow: 0 0 30px rgba(225,6,0,0.35);
    }
    .forums-hero h2 .accent {
        color: #e10600;
        text-shadow: 0 0 20px rgba(225,6,0,0.8);
    }

    /* ── Stat cards ──────────────────────────────────────── */
    .stat-card {
        position: relative;
        padding: 1.5rem;
        border-radius: 1rem;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.1);
        background: linear-gradient(135deg, #1a1a28 0%, #0f0f15 100%);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 0 25px rgba(225,6,0,0.35);
    }
    .stat-card::before {
        content: "";
        position: absolute; top: 0; left: 0; bottom: 0;
        width: 4px;
        background: #e10600;
    }
    .stat-card .num {
        font-family: "Audiowide", sans-serif;
        font-size: 2.5rem;
        font-weight: 800;
        line-height: 1;
        color: #fff;
        text-shadow: 0 0 20px rgba(225,6,0,0.4);
    }
    .stat-card .label {
        font-size: 0.75rem;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: rgba(255,255,255,0.5);
        margin-top: 0.5rem;
    }

    /* ── Search + sort bar ───────────────────────────────── */
    .filter-input, .filter-select {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.15);
        color: white;
        padding: 0.65rem 1rem;
        border-radius: 9999px;
        transition: border-color 0.25s ease, box-shadow 0.25s ease;
    }
    .filter-input::placeholder { color: rgba(255,255,255,0.4); }
    .filter-input:focus,
    .filter-select:focus {
        outline: none;
        border-color: rgba(225,6,0,0.9);
        box-shadow: 0 0 15px rgba(225,6,0,0.35);
    }
    .filter-select option { background: #15151e; color: white; }

    .btn-f1 {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.65rem 1.5rem;
        background: linear-gradient(90deg, #e10600 0%, #a30400 100%);
        border-radius: 9999px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: white;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .btn-f1:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(225,6,0,0.55); }

    /* ── Race row card ───────────────────────────────────── */
    .race-row {
        position: relative;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        cursor: pointer;
        transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
        overflow: hidden;
    }
    .race-row::before {
        content: "";
        position: absolute; top: 0; left: 0; bottom: 0;
        width: 4px;
        background: rgba(225,6,0,0.4);
        transition: background 0.25s ease, box-shadow 0.25s ease;
    }
    .race-row:hover {
        transform: translateX(4px);
        background: rgba(225,6,0,0.08);
        border-color: rgba(225,6,0,0.45);
        box-shadow: 0 0 25px rgba(225,6,0,0.25);
    }
    .race-row:hover::before {
        background: #e10600;
        box-shadow: 0 0 15px rgba(225,6,0,0.8);
    }
    .round-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 3rem; height: 3rem;
        background: rgba(0,0,0,0.4);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 9999px;
        font-family: "Audiowide", sans-serif;
        font-weight: 800;
        color: white;
        font-size: 0.85rem;
        letter-spacing: 1px;
    }

    /* ── Pagination ─────────────────────────────────────── */
    .f1-pagination {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        flex-wrap: wrap;
    }
    .f1-pagination a,
    .f1-pagination span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2.5rem;
        height: 2.5rem;
        padding: 0 0.75rem;
        border-radius: 0.5rem;
        font-family: 'Audiowide', sans-serif;
        font-size: 0.7rem;
        letter-spacing: 1px;
        border: 1px solid rgba(255,255,255,0.1);
        background: rgba(255,255,255,0.04);
        color: rgba(255,255,255,0.6);
        transition: background 0.2s, border-color 0.2s, color 0.2s;
        text-decoration: none;
    }
    .f1-pagination a:hover {
        background: rgba(225,6,0,0.15);
        border-color: rgba(225,6,0,0.5);
        color: white;
    }
    .f1-pagination span.active {
        background: #e10600;
        border-color: #e10600;
        color: white;
        box-shadow: 0 0 14px rgba(225,6,0,0.5);
    }
    .f1-pagination span.disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    @media (prefers-reduced-motion: reduce) {
        .reveal, .reveal-scale, .reveal-stagger > * {
            opacity: 1 !important; transform: none !important; transition: none !important;
        }
    }
</style>

{{-- ───────────────────────── HERO ───────────────────────── --}}
<section class="forums-hero min-h-[360px] text-white">
    <div class="hero-stripe top"></div>
    <div class="hero-stripe bottom"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 py-14 text-center">
        <div class="reveal-scale">
            <p class="audiowide-regular text-xs md:text-sm text-white/60 tracking-[6px] mb-2">
                COMMUNITY
            </p>
            <h2 class="audiowide-regular text-4xl md:text-5xl lg:text-6xl font-bold mb-4">
                Race <span class="accent">Forums</span>
            </h2>
            <p class="text-gray-300 max-w-2xl mx-auto text-base md:text-lg">
                Discuss races, share predictions, and relive the action with fellow F1 fans.
            </p>
        </div>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20">

    {{-- ───────────────────────── STATS ───────────────────────── --}}
    <div class="reveal-stagger grid grid-cols-1 sm:grid-cols-3 gap-6 mb-12">
        <div class="stat-card">
            <p class="num">{{ number_format($thanksLeft) }}</p>
            <p class="label">Likes Left</p>
        </div>
        <div class="stat-card">
            <p class="num">{{ number_format($totalPosts) }}</p>
            <p class="label">Total Posts</p>
        </div>
        <div class="stat-card">
            <p class="num">{{ number_format($totalRaces) }}</p>
            <p class="label">Total Forums</p>
        </div>
    </div>

    {{-- ───────────────────────── SEARCH + SORT ───────────────────────── --}}
    <form method="GET" class="flex flex-col sm:flex-row gap-3 mb-8 items-stretch sm:items-center reveal">
        <div class="relative flex-1">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z"/>
            </svg>
            <input type="text" name="search" value="{{ $search }}" placeholder="Search races..."
                   class="filter-input w-full pl-10 pr-4 audiowide-regular">
        </div>
        <select name="season" class="filter-select audiowide-regular">
            <option value="">All Seasons</option>
            @foreach($seasons as $s)
                <option value="{{ $s }}" {{ (string) $season === (string) $s ? 'selected' : '' }}>{{ $s }}</option>
            @endforeach
        </select>
        <select name="sort" class="filter-select audiowide-regular">
            <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>Latest Race</option>
            <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>Oldest Race</option>
            <option value="most_posts" {{ $sort == 'most_posts' ? 'selected' : '' }}>Most Posts</option>
            <option value="least_posts" {{ $sort == 'least_posts' ? 'selected' : '' }}>Least Posts</option>
        </select>
        <button type="submit" class="btn-f1 audiowide-regular">Apply</button>
    </form>

    {{-- ───────────────────────── FORUM LIST ───────────────────────── --}}
    <h3 class="section-title audiowide-regular text-xl md:text-2xl font-bold uppercase text-white mb-6 reveal">
        Race Threads
    </h3>

    <div class="space-y-3 pb-20">
        @forelse($races as $race)
            <div onclick="window.location.href='{{ route('forums.show', $race->id) }}'"
                 class="race-row audiowide-regular">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4 min-w-0">
                        <span class="round-badge shrink-0">R{{ $race->round ?? '—' }}</span>
                        <div class="min-w-0">
                            <h3 class="text-lg md:text-xl font-bold text-white truncate">{{ $race->name }}</h3>
                            <p class="text-xs text-white/50 tracking-widest uppercase mt-0.5">
                                {{ \Carbon\Carbon::parse($race->date)->format('d M Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="hidden md:flex items-center gap-6 text-sm text-white/60 shrink-0">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <span><span class="text-white font-semibold">{{ $race->forum_posts_count }}</span> posts</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $race->last_post_at ? \Carbon\Carbon::parse($race->last_post_at)->diffForHumans() : '—' }}</span>
                        </div>
                    </div>

                    <svg class="w-5 h-5 text-white/30 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>

                {{-- mobile meta --}}
                <div class="flex md:hidden items-center gap-4 mt-3 pt-3 border-t border-white/10 text-xs text-white/50">
                    <span>💬 {{ $race->forum_posts_count }} posts</span>
                    <span>🕒 {{ $race->last_post_at ? \Carbon\Carbon::parse($race->last_post_at)->diffForHumans() : '—' }}</span>
                </div>
            </div>
        @empty
            <div class="bg-white/5 border border-white/10 rounded-xl p-12 text-center">
                <p class="text-gray-400 audiowide-regular">No races found.</p>
            </div>
        @endforelse
    </div>

    {{-- ── Pagination ── --}}
    @if($races->hasPages())
    <div class="f1-pagination pb-20 reveal">
        {{-- Previous --}}
        @if($races->onFirstPage())
            <span class="disabled">← Prev</span>
        @else
            <a href="{{ $races->previousPageUrl() }}">← Prev</a>
        @endif

        {{-- Page numbers --}}
        @foreach($races->getUrlRange(1, $races->lastPage()) as $page => $url)
            @if($page == $races->currentPage())
                <span class="active">{{ $page }}</span>
            @else
                <a href="{{ $url }}">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Next --}}
        @if($races->hasMorePages())
            <a href="{{ $races->nextPageUrl() }}">Next →</a>
        @else
            <span class="disabled">Next →</span>
        @endif
    </div>
    @endif

</div>

<script>
(function(){
    const targets = document.querySelectorAll('.reveal, .reveal-scale, .reveal-stagger');
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
    }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' });
    targets.forEach(el => io.observe(el));
})();
</script>
</x-app-layout>
