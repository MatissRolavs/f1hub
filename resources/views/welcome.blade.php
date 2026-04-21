<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>F1Hub — Your Formula 1 Headquarters</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }

        body {
            background: #0a0a0f;
            color: white;
            margin: 0;
            font-family: 'Figtree', sans-serif;
            overflow-x: hidden;
        }

        /* ── Scroll reveal ───────────────────────── */
        .reveal, .reveal-scale, .reveal-left, .reveal-right {
            opacity: 0;
            transition: opacity 0.9s cubic-bezier(.2,.65,.3,1), transform 0.9s cubic-bezier(.2,.65,.3,1);
            will-change: opacity, transform;
        }
        .reveal       { transform: translateY(50px); }
        .reveal-scale { transform: scale(0.94); }
        .reveal-left  { transform: translateX(-50px); }
        .reveal-right { transform: translateX(50px); }
        .is-visible   { opacity: 1 !important; transform: none !important; }

        /* ── Red accent ──────────────────────────── */
        .accent { color: #e10600; }
        .accent-glow { color: #e10600; text-shadow: 0 0 25px rgba(225,6,0,0.7); }

        /* ── Hero ────────────────────────────────── */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            overflow: hidden;
        }
        .hero-bg {
            position: absolute; inset: 0;
            background-image: url('{{ asset("images/racetrack.jpg") }}');
            background-size: cover;
            background-position: center 40%;
            transform: scale(1.05);
            transition: transform 8s ease;
        }
        .hero-bg.loaded { transform: scale(1); }
        .hero-overlay {
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse at 50% 40%, rgba(225,6,0,0.25) 0%, transparent 55%),
                linear-gradient(to bottom, rgba(10,10,15,0.5) 0%, rgba(10,10,15,0.92) 100%);
        }
        .hero::before {
            content: "";
            position: absolute; inset: 0;
            background-image: repeating-linear-gradient(
                45deg,
                rgba(255,255,255,0.018) 0 14px,
                transparent 14px 28px
            );
            pointer-events: none;
            z-index: 1;
        }
        .hero-stripe {
            position: absolute; left: 0;
            height: 4px; width: 100%;
            z-index: 3;
        }
        .hero-stripe.top {
            top: 0;
            background: linear-gradient(90deg, #e10600 0%, #e10600 60%, transparent 100%);
            box-shadow: 0 0 24px rgba(225,6,0,0.9);
        }
        .hero-stripe.bottom {
            bottom: 0;
            background: linear-gradient(270deg, #e10600 0%, #e10600 60%, transparent 100%);
            box-shadow: 0 0 24px rgba(225,6,0,0.9);
        }

        /* ── Driver card hover ───────────────────── */
        .f1-card {
            transition: transform 0.35s ease, box-shadow 0.35s ease;
            box-shadow: 0 4px 20px rgba(0,0,0,0.4);
        }
        .f1-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 0 35px rgba(225,6,0,0.5), 0 10px 30px rgba(0,0,0,0.6);
        }

        /* ── Countdown pulse ─────────────────────── */
        @keyframes countdown-pulse {
            0%, 100% { box-shadow: 0 0 10px rgba(255,255,255,0.5), 0 0 25px rgba(0,0,0,1); border-color: rgba(255,255,255,0.8); }
            50%      { box-shadow: 0 0 18px rgba(225,6,0,0.7), 0 0 35px rgba(0,0,0,1); border-color: rgba(225,6,0,0.9); }
        }
        .countdown-box { animation: countdown-pulse 3.2s ease-in-out infinite; }

        /* ── Hero content ────────────────────────── */
        .hero-content {
            position: relative;
            z-index: 10;
            padding: 2rem 1.5rem;
            max-width: 900px;
        }
        .hero-eyebrow {
            font-family: 'Audiowide', sans-serif;
            font-size: 0.75rem;
            letter-spacing: 6px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.5);
            margin-bottom: 1.25rem;
        }
        .hero-title {
            font-family: 'Audiowide', sans-serif;
            font-size: clamp(3rem, 10vw, 7rem);
            font-weight: 400;
            line-height: 1.05;
            letter-spacing: 2px;
            margin: 0 0 1.5rem;
        }
        .hero-sub {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.6);
            max-width: 520px;
            margin: 0 auto 2.5rem;
            line-height: 1.7;
        }
        .hero-cta-group { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
        .btn-primary {
            font-family: 'Audiowide', sans-serif;
            font-size: 0.75rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 1rem 2.5rem;
            background: #e10600;
            color: white;
            border-radius: 9999px;
            text-decoration: none;
            transition: box-shadow 0.25s, background 0.25s;
        }
        .btn-primary:hover {
            background: #c20500;
            box-shadow: 0 0 28px rgba(225,6,0,0.6);
        }
        .btn-ghost {
            font-family: 'Audiowide', sans-serif;
            font-size: 0.75rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 1rem 2.5rem;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            border-radius: 9999px;
            text-decoration: none;
            transition: background 0.25s, border-color 0.25s;
        }
        .btn-ghost:hover {
            background: rgba(255,255,255,0.12);
            border-color: rgba(255,255,255,0.4);
        }

        /* ── Scroll indicator ────────────────────── */
        .scroll-indicator {
            position: absolute;
            bottom: 2.5rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.4rem;
            opacity: 0.4;
            animation: bounce 2s infinite;
        }
        .scroll-indicator span {
            font-family: 'Audiowide', sans-serif;
            font-size: 0.55rem;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .scroll-indicator svg { width: 20px; height: 20px; }
        @keyframes bounce {
            0%, 100% { transform: translateX(-50%) translateY(0); }
            50%       { transform: translateX(-50%) translateY(6px); }
        }

        /* ── Stats bar ───────────────────────────── */
        .stats-bar {
            background: rgba(255,255,255,0.03);
            border-top: 1px solid rgba(255,255,255,0.06);
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .stat-item {
            text-align: center;
            padding: 2rem 1rem;
            border-right: 1px solid rgba(255,255,255,0.06);
        }
        .stat-item:last-child { border-right: none; }
        .stat-number {
            font-family: 'Audiowide', sans-serif;
            font-size: 2.5rem;
            font-weight: 400;
            color: #e10600;
            line-height: 1;
            text-shadow: 0 0 30px rgba(225,6,0,0.4);
        }
        .stat-desc {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.4);
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 0.5rem;
        }

        /* ── Section ─────────────────────────────── */
        section { padding: 6rem 1.5rem; }
        .section-inner { max-width: 1200px; margin: 0 auto; }

        .section-eyebrow {
            font-family: 'Audiowide', sans-serif;
            font-size: 0.65rem;
            letter-spacing: 5px;
            text-transform: uppercase;
            color: #e10600;
            margin-bottom: 1rem;
        }
        .section-title-text {
            font-family: 'Audiowide', sans-serif;
            font-size: clamp(1.8rem, 4vw, 3rem);
            font-weight: 400;
            margin: 0 0 1rem;
            line-height: 1.2;
        }
        .section-sub {
            font-size: 1rem;
            color: rgba(255,255,255,0.5);
            max-width: 480px;
            line-height: 1.7;
        }

        /* ── Feature grid ────────────────────────── */
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 1.25rem;
            overflow: hidden;
        }
        .feature-card {
            background: #0f0f17;
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
            transition: background 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        .feature-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 0% 0%, rgba(225,6,0,0.08) 0%, transparent 60%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .feature-card:hover { background: #141420; }
        .feature-card:hover::before { opacity: 1; }
        .feature-icon {
            font-size: 2.25rem;
            margin-bottom: 1.25rem;
            display: block;
        }
        .feature-name {
            font-family: 'Audiowide', sans-serif;
            font-size: 1rem;
            font-weight: 400;
            margin: 0 0 0.75rem;
            color: white;
        }
        .feature-desc {
            font-size: 0.875rem;
            color: rgba(255,255,255,0.45);
            line-height: 1.7;
            margin: 0 0 1.5rem;
        }
        .feature-link {
            font-family: 'Audiowide', sans-serif;
            font-size: 0.65rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #e10600;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: gap 0.2s;
        }
        .feature-card:hover .feature-link { gap: 0.7rem; }
        .feature-number {
            position: absolute;
            top: 1.5rem; right: 1.75rem;
            font-family: 'Audiowide', sans-serif;
            font-size: 3.5rem;
            font-weight: 400;
            color: rgba(255,255,255,0.04);
            line-height: 1;
            pointer-events: none;
        }

        /* ── Big feature highlight ───────────────── */
        .highlight-block {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }
        @media (max-width: 768px) {
            .highlight-block { grid-template-columns: 1fr; gap: 2rem; }
            .highlight-block.reverse { direction: ltr; }
        }
        .highlight-block.reverse { direction: rtl; }
        .highlight-block.reverse > * { direction: ltr; }

        .highlight-visual {
            background: #0f0f17;
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 1.25rem;
            overflow: hidden;
            aspect-ratio: 4/3;
            position: relative;
        }

        /* Mock driver card */
        .mock-card {
            background: linear-gradient(135deg, #1e1e2e, #141420);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 0.75rem;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.75rem;
        }
        .mock-avatar {
            width: 42px; height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e10600, #800000);
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Audiowide', sans-serif;
            font-size: 0.65rem;
            color: white;
        }
        .mock-name { font-family: 'Audiowide', sans-serif; font-size: 0.75rem; color: white; }
        .mock-team { font-size: 0.7rem; color: rgba(255,255,255,0.4); margin-top: 0.2rem; }
        .mock-pts {
            margin-left: auto;
            font-family: 'Audiowide', sans-serif;
            font-size: 1rem;
            color: #e10600;
        }
        .mock-pos {
            font-size: 0.6rem;
            color: rgba(255,255,255,0.3);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Mock race card */
        .mock-race {
            background: linear-gradient(135deg, #1e1e2e, #141420);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin-bottom: 0.75rem;
        }
        .mock-race-flag { font-size: 1.5rem; margin-bottom: 0.5rem; }
        .mock-race-name { font-family: 'Audiowide', sans-serif; font-size: 0.75rem; color: white; }
        .mock-race-date { font-size: 0.7rem; color: rgba(255,255,255,0.35); margin-top: 0.3rem; }
        .mock-badge {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border-radius: 9999px;
            font-size: 0.6rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-family: 'Audiowide', sans-serif;
            margin-top: 0.6rem;
        }
        .mock-badge.upcoming { background: rgba(225,6,0,0.2); color: #e10600; border: 1px solid rgba(225,6,0,0.4); }
        .mock-badge.completed { background: rgba(255,255,255,0.07); color: rgba(255,255,255,0.4); border: 1px solid rgba(255,255,255,0.12); }

        /* Mock prediction */
        .mock-prediction {
            background: linear-gradient(135deg, #1e1e2e, #141420);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 0.75rem;
            padding: 1.25rem;
        }
        .mock-podium { display: flex; gap: 0.5rem; align-items: flex-end; justify-content: center; margin: 1rem 0; }
        .mock-podium-slot {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.3rem;
        }
        .mock-podium-slot .label { font-family: 'Audiowide', sans-serif; font-size: 0.55rem; color: rgba(255,255,255,0.3); }
        .mock-podium-bar {
            width: 48px;
            border-radius: 4px 4px 0 0;
            background: rgba(255,255,255,0.08);
        }
        .p1-bar { height: 60px; background: linear-gradient(to top, #f59e0b, #fcd34d); }
        .p2-bar { height: 44px; background: linear-gradient(to top, #9ca3af, #d1d5db); }
        .p3-bar { height: 32px; background: linear-gradient(to top, #f97316, #fdba74); }

        /* ── Forum mock ──────────────────────────── */
        .forum-mock {
            background: #0f0f17;
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 1.25rem;
            overflow: hidden;
        }
        .forum-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .forum-header-dot { width: 8px; height: 8px; border-radius: 50%; background: #e10600; box-shadow: 0 0 8px rgba(225,6,0,0.6); }
        .forum-header-title { font-family: 'Audiowide', sans-serif; font-size: 0.75rem; color: rgba(255,255,255,0.7); }
        .forum-post {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            display: flex;
            gap: 1rem;
            align-items: flex-start;
            transition: background 0.2s;
        }
        .forum-post:hover { background: rgba(255,255,255,0.02); }
        .forum-post:last-child { border-bottom: none; }
        .forum-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Audiowide', sans-serif;
            font-size: 0.6rem;
            color: white;
        }
        .forum-post-title { font-size: 0.85rem; color: white; margin: 0 0 0.3rem; }
        .forum-post-meta { font-size: 0.7rem; color: rgba(255,255,255,0.3); }
        .forum-post-likes {
            margin-left: auto;
            font-size: 0.7rem;
            color: rgba(255,255,255,0.3);
            display: flex;
            align-items: center;
            gap: 0.3rem;
            flex-shrink: 0;
        }

        /* ── CTA Section ─────────────────────────── */
        .cta-section {
            position: relative;
            overflow: hidden;
            text-align: center;
            padding: 8rem 1.5rem;
        }
        .cta-section::before {
            content: "";
            position: absolute; inset: 0;
            background: radial-gradient(ellipse at 50% 50%, rgba(225,6,0,0.18) 0%, transparent 60%);
            pointer-events: none;
        }
        .cta-section::after {
            content: "";
            position: absolute; inset: 0;
            background-image: repeating-linear-gradient(
                45deg,
                rgba(255,255,255,0.015) 0 14px,
                transparent 14px 28px
            );
            pointer-events: none;
        }
        .cta-inner { position: relative; z-index: 1; }

        /* ── Footer ──────────────────────────────── */
        .site-footer {
            border-top: 1px solid rgba(255,255,255,0.06);
            padding: 2.5rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .footer-logo { font-family: 'Audiowide', sans-serif; font-size: 1rem; letter-spacing: 2px; }
        .footer-logo span { color: #e10600; }
        .footer-links { display: flex; gap: 1.5rem; }
        .footer-link { font-size: 0.75rem; color: rgba(255,255,255,0.35); text-decoration: none; letter-spacing: 1px; transition: color 0.2s; }
        .footer-link:hover { color: rgba(255,255,255,0.7); }

        @media (prefers-reduced-motion: reduce) {
            .reveal, .reveal-scale, .reveal-left, .reveal-right {
                opacity: 1 !important; transform: none !important; transition: none !important;
            }
        }
    </style>
</head>
<body>

@include('layouts.navigation')

{{-- ───────── HERO ───────── --}}
<section class="hero">
    <div class="hero-bg" id="heroBg"></div>
    <div class="hero-overlay"></div>
    <div class="hero-stripe top"></div>
    <div class="hero-stripe bottom"></div>

    <div class="hero-content">
        <p class="hero-eyebrow reveal">Your Formula 1 Headquarters</p>
        <h1 class="hero-title reveal">
            FORMULA<br><span class="accent-glow">1</span> HUB
        </h1>
        <p class="hero-sub reveal">
            Live standings, driver profiles, race results, community forums, and a prediction game — everything F1 in one place.
        </p>
        <div class="hero-cta-group reveal">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-primary">Go to Dashboard</a>
                <a href="{{ route('drivers.index') }}" class="btn-ghost">Browse Drivers</a>
            @else
                <a href="{{ route('register') }}" class="btn-primary">Get Started Free</a>
                <a href="{{ route('drivers.index') }}" class="btn-ghost">Explore the Grid</a>
            @endauth
        </div>
    </div>

    <div class="scroll-indicator">
        <span>Scroll</span>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 5v14M5 12l7 7 7-7"/>
        </svg>
    </div>
</section>

{{-- ───────── STATS BAR ───────── --}}
<div class="stats-bar">
    <div style="max-width:1200px;margin:0 auto;display:grid;grid-template-columns:repeat(4,1fr);">
        <div class="stat-item reveal">
            <div class="stat-number">75+</div>
            <div class="stat-desc">Years of Race Data</div>
        </div>
        <div class="stat-item reveal">
            <div class="stat-number">20</div>
            <div class="stat-desc">Current Drivers</div>
        </div>
        <div class="stat-item reveal">
            <div class="stat-number">24</div>
            <div class="stat-desc">Races This Season</div>
        </div>
        <div class="stat-item reveal">
            <div class="stat-number">10</div>
            <div class="stat-desc">Constructor Teams</div>
        </div>
    </div>
</div>

{{-- ───────── FEATURES ───────── --}}
<section>
    <div class="section-inner">
        <p class="section-eyebrow text-center reveal">Everything you need</p>
        <h2 class="section-title-text text-center reveal" style="margin-bottom:0.5rem;">Built for F1 fans</h2>
        <p class="section-sub text-center reveal" style="margin:0 auto 3.5rem;">From rookie to superfan — explore every corner of Formula 1.</p>

        <div class="feature-grid reveal-scale">

            <a href="{{ route('drivers.index') }}" class="feature-card">
                <span class="feature-number">01</span>
                <span class="feature-icon">🏎️</span>
                <h3 class="feature-name">Driver Profiles</h3>
                <p class="feature-desc">Full profiles for every driver on the grid. Career stats, season results, nationality, team history, and side-by-side season comparisons.</p>
                <span class="feature-link">Explore drivers →</span>
            </a>

            <a href="{{ route('races.index') }}" class="feature-card">
                <span class="feature-number">02</span>
                <span class="feature-icon">🏁</span>
                <h3 class="feature-name">Race Calendar & Results</h3>
                <p class="feature-desc">The full season calendar at a glance. Upcoming races, past results, lap times, fastest laps, and podium finishers for every Grand Prix.</p>
                <span class="feature-link">View races →</span>
            </a>

            <a href="{{ route('standings.index') }}" class="feature-card">
                <span class="feature-number">03</span>
                <span class="feature-icon">🏆</span>
                <h3 class="feature-name">Championship Standings</h3>
                <p class="feature-desc">Live driver and constructor championship tables. Track points gaps, wins, and see who's in contention for the title.</p>
                <span class="feature-link">See standings →</span>
            </a>

            <a href="{{ route('forums.index') }}" class="feature-card">
                <span class="feature-number">04</span>
                <span class="feature-icon">💬</span>
                <h3 class="feature-name">Race Forums</h3>
                <p class="feature-desc">Every Grand Prix has its own forum. Post your take, react to incidents, and debate the results with the community.</p>
                <span class="feature-link">Join the conversation →</span>
            </a>

            @auth
                <a href="{{ route('game.index') }}" class="feature-card">
            @else
                <a href="{{ route('register') }}" class="feature-card">
            @endauth
                <span class="feature-number">05</span>
                <span class="feature-icon">🎯</span>
                <h3 class="feature-name">Prediction Game</h3>
                <p class="feature-desc">Pick your podium before every race, earn points for correct calls, and climb the global leaderboard. Can you out-predict the pack?</p>
                <span class="feature-link">Make your picks →</span>
            </a>

            @auth
                <a href="{{ route('game.leaderboard') }}" class="feature-card">
            @else
                <a href="{{ route('register') }}" class="feature-card">
            @endauth
                <span class="feature-number">06</span>
                <span class="feature-icon">📊</span>
                <h3 class="feature-name">Leaderboard</h3>
                <p class="feature-desc">See how you stack up against other fans. Season-long ranking, accuracy rates, and streaks — the ultimate F1 bragging rights.</p>
                <span class="feature-link">View leaderboard →</span>
            </a>

        </div>
    </div>
</section>

{{-- ───────── FEATURED DRIVERS ───────── --}}
<section style="padding:6rem 1.5rem;">
    <div class="section-inner">
        <p class="section-eyebrow reveal">Current Grid</p>
        <h2 class="section-title-text reveal" style="margin-bottom:3rem;">Featured Drivers</h2>

        <div class="reveal-scale" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.5rem;">
            @foreach($drivers->take(3) as $driver)
                @php
                    $constructorName = $driver->latestStanding->constructor->name ?? 'Unknown';
                    $bgColor  = config('f1.team_colors.' . $constructorName, config('f1.default_team_color'));
                    $flagCode = config('f1.nationality_flags.' . $driver->nationality, config('f1.default_flag_code'));
                    $flagUrl  = "https://flagcdn.com/w40/" . $flagCode . ".png";
                @endphp
                <a href="{{ route('drivers.show', $driver) }}" class="block">
                    <div class="f1-card group relative rounded-2xl overflow-hidden"
                         style="background-color:{{ $bgColor }};height:740px;">
                        <div class="overflow-hidden rounded-t-2xl">
                            <img src="https://media.formula1.com/image/upload/f_webp,c_limit,q_50,w_640/content/dam/fom-website/drivers/2025Drivers/{{ $driver->family_name }}"
                                 alt="{{ $driver->given_name }} {{ $driver->family_name }}"
                                 class="w-full h-[520px] object-cover bg-white rounded-t-2xl transform transition-transform duration-700 group-hover:scale-110"
                                 onerror="this.onerror=null;this.src='data:image/png;base64,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';">
                        </div>
                        <div class="p-6 text-white flex flex-col h-[220px] rounded-b-2xl">
                            <div class="flex items-center justify-between mb-3">
                                <h5 class="text-xl font-bold leading-tight audiowide-regular">
                                    {{ $driver->given_name }} {{ $driver->family_name }}
                                </h5>
                                <img src="{{ $flagUrl }}" alt="{{ $driver->nationality }}" class="w-8 h-5 rounded shadow">
                            </div>
                            <div class="flex-1 flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold mb-2 audiowide-regular">Season Stats</h3>
                                    <ul class="space-y-1 text-base audiowide-regular text-left">
                                        <li><strong>Position:</strong> {{ $driver->latestStanding->position ?? '—' }}</li>
                                        <li><strong>Points:</strong> {{ $driver->latestStanding->points ?? '—' }}</li>
                                        <li><strong>Wins:</strong> {{ $driver->latestStanding->wins ?? '—' }}</li>
                                    </ul>
                                </div>
                                <div class="text-5xl font-bold audiowide-regular opacity-80">
                                    #{{ $driver->permanent_number ?? '—' }}
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-lg audiowide-regular mt-4">
                                <p></p>
                                <p><strong>Team:</strong> {{ $constructorName }}</p>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="text-center mt-10 reveal">
            <a href="{{ route('drivers.index') }}" class="btn-ghost" style="display:inline-block;">View All Drivers</a>
        </div>
    </div>
</section>

{{-- ───────── NEXT RACE COUNTDOWN ───────── --}}
<section style="background:#0d0d14;padding:5rem 1.5rem;">
    <div class="section-inner">
        <div class="reveal-scale" style="background:linear-gradient(135deg,#15151e,#1a1a28);border:1px solid rgba(255,255,255,0.08);border-radius:1.25rem;padding:3rem 2rem;text-align:center;position:relative;overflow:hidden;">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#e10600,#e10600 60%,transparent);box-shadow:0 0 20px rgba(225,6,0,0.7);"></div>

            <p class="section-eyebrow" style="margin-bottom:0.75rem;">On the Clock</p>
            <h2 class="section-title-text" style="margin-bottom:0.5rem;">Next Race Countdown</h2>
            <p style="color:rgba(255,255,255,0.5);font-size:0.95rem;margin-bottom:2.5rem;">
                @if($nextRace)
                    {{ $nextRace['raceName'] }} &mdash;
                    {{ $nextRace['Circuit']['Location']['locality'] ?? '' }},
                    {{ $nextRace['Circuit']['Location']['country'] ?? '' }}
                @else
                    No upcoming race scheduled
                @endif
            </p>

            @if($nextRace)
                <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:1.5rem;">
                    @foreach(['days-landing' => 'Days', 'hours-landing' => 'Hours', 'minutes-landing' => 'Minutes', 'seconds-landing' => 'Seconds'] as $id => $label)
                        <div style="display:flex;flex-direction:column;align-items:center;gap:0.75rem;">
                            <span id="{{ $id }}"
                                  class="audiowide-regular countdown-box"
                                  style="font-size:clamp(2.5rem,6vw,4rem);font-weight:900;color:white;background:rgba(0,0,0,0.4);backdrop-filter:blur(8px);width:clamp(5rem,12vw,8rem);height:clamp(5rem,12vw,8rem);display:flex;align-items:center;justify-content:center;border:2px solid rgba(255,255,255,0.7);border-radius:0.75rem;">
                                0
                            </span>
                            <span style="font-family:'Audiowide',sans-serif;font-size:0.6rem;letter-spacing:3px;text-transform:uppercase;color:rgba(255,255,255,0.4);">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>

{{-- ───────── STANDINGS HIGHLIGHT ───────── --}}
<section style="background:#0d0d14; padding:6rem 1.5rem;">
    <div class="section-inner">
        <div class="highlight-block">
            <div class="reveal-left">
                <p class="section-eyebrow">Live Data</p>
                <h2 class="section-title-text">Championship standings, always up to date</h2>
                <p class="section-sub" style="margin-bottom:2rem;">
                    Driver and constructor points tables refreshed with every race. See who's leading the title fight and by how much.
                </p>
                <a href="{{ route('standings.index') }}" class="btn-ghost" style="display:inline-block;">View standings</a>
            </div>
            <div class="reveal-right">
                <div class="highlight-visual" style="padding:1.5rem;">
                    <div class="mock-card">
                        <div class="mock-avatar">VER</div>
                        <div>
                            <div class="mock-name">Max Verstappen</div>
                            <div class="mock-team">Red Bull Racing</div>
                        </div>
                        <div style="margin-left:auto;text-align:right;">
                            <div class="mock-pts">393</div>
                            <div class="mock-pos">P1</div>
                        </div>
                    </div>
                    <div class="mock-card">
                        <div class="mock-avatar" style="background:linear-gradient(135deg,#ED1131,#800000);">LEC</div>
                        <div>
                            <div class="mock-name">Charles Leclerc</div>
                            <div class="mock-team">Ferrari</div>
                        </div>
                        <div style="margin-left:auto;text-align:right;">
                            <div class="mock-pts" style="color:rgba(255,255,255,0.6);font-size:0.9rem;">307</div>
                            <div class="mock-pos">P2</div>
                        </div>
                    </div>
                    <div class="mock-card">
                        <div class="mock-avatar" style="background:linear-gradient(135deg,#00D7B6,#006655);">NOR</div>
                        <div>
                            <div class="mock-name">Lando Norris</div>
                            <div class="mock-team">McLaren</div>
                        </div>
                        <div style="margin-left:auto;text-align:right;">
                            <div class="mock-pts" style="color:rgba(255,255,255,0.6);font-size:0.9rem;">285</div>
                            <div class="mock-pos">P3</div>
                        </div>
                    </div>
                    <div style="text-align:center;padding-top:0.5rem;">
                        <span style="font-family:'Audiowide',sans-serif;font-size:0.55rem;letter-spacing:2px;color:rgba(255,255,255,0.2);text-transform:uppercase;">+ 17 more drivers</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ───────── RACE CALENDAR HIGHLIGHT ───────── --}}
<section style="padding:6rem 1.5rem;">
    <div class="section-inner">
        <div class="highlight-block reverse">
            <div class="reveal-right">
                <p class="section-eyebrow">Full Calendar</p>
                <h2 class="section-title-text">Every race, every result</h2>
                <p class="section-sub" style="margin-bottom:2rem;">
                    The complete season calendar with upcoming race countdowns, past results, and Grand Prix details for every circuit on the calendar.
                </p>
                <a href="{{ route('races.index') }}" class="btn-ghost" style="display:inline-block;">View calendar</a>
            </div>
            <div class="reveal-left">
                <div class="highlight-visual" style="padding:1.5rem;">
                    <div class="mock-race">
                        <div class="mock-race-flag">🇲🇨</div>
                        <div class="mock-race-name">Monaco Grand Prix</div>
                        <div class="mock-race-date">25 May · Circuit de Monaco</div>
                        <div><span class="mock-badge upcoming">Upcoming</span></div>
                    </div>
                    <div class="mock-race">
                        <div class="mock-race-flag">🇪🇸</div>
                        <div class="mock-race-name">Spanish Grand Prix</div>
                        <div class="mock-race-date">1 Jun · Circuit de Barcelona</div>
                        <div><span class="mock-badge upcoming">Upcoming</span></div>
                    </div>
                    <div class="mock-race">
                        <div class="mock-race-flag">🇧🇭</div>
                        <div class="mock-race-name">Bahrain Grand Prix</div>
                        <div class="mock-race-date">2 Mar · Bahrain International Circuit</div>
                        <div><span class="mock-badge completed">Results Available</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ───────── PREDICTION GAME HIGHLIGHT ───────── --}}
<section style="background:#0d0d14; padding:6rem 1.5rem;">
    <div class="section-inner">
        <div class="highlight-block">
            <div class="reveal-left">
                <p class="section-eyebrow">Prediction Game</p>
                <h2 class="section-title-text">Think you know who wins?</h2>
                <p class="section-sub" style="margin-bottom:2rem;">
                    Before each race, lock in your predicted podium. Earn points for every correct pick and compete with fans around the world on the season leaderboard.
                </p>
                @auth
                    <a href="{{ route('game.play') }}" class="btn-primary" style="display:inline-block;">Make a prediction</a>
                @else
                    <a href="{{ route('register') }}" class="btn-primary" style="display:inline-block;">Join to play</a>
                @endauth
            </div>
            <div class="reveal-right">
                <div class="highlight-visual" style="padding:1.5rem;display:flex;align-items:center;justify-content:center;">
                    <div class="mock-prediction" style="width:100%;">
                        <div style="font-family:'Audiowide',sans-serif;font-size:0.7rem;color:rgba(255,255,255,0.4);letter-spacing:2px;text-transform:uppercase;text-align:center;margin-bottom:0.5rem;">Your Podium Prediction</div>
                        <div style="font-family:'Audiowide',sans-serif;font-size:0.85rem;color:white;text-align:center;margin-bottom:0.25rem;">Monaco Grand Prix</div>
                        <div class="mock-podium">
                            <div class="mock-podium-slot">
                                <div class="mock-avatar" style="width:36px;height:36px;font-size:0.55rem;background:linear-gradient(135deg,#9ca3af,#d1d5db);color:#000;">NOR</div>
                                <div class="mock-podium-bar p2-bar"></div>
                                <div class="label">P2</div>
                            </div>
                            <div class="mock-podium-slot">
                                <div class="mock-avatar" style="width:36px;height:36px;font-size:0.55rem;background:linear-gradient(135deg,#f59e0b,#fcd34d);color:#000;">VER</div>
                                <div class="mock-podium-bar p1-bar"></div>
                                <div class="label">P1</div>
                            </div>
                            <div class="mock-podium-slot">
                                <div class="mock-avatar" style="width:36px;height:36px;font-size:0.55rem;background:linear-gradient(135deg,#f97316,#fdba74);color:#000;">LEC</div>
                                <div class="mock-podium-bar p3-bar"></div>
                                <div class="label">P3</div>
                            </div>
                        </div>
                        <div style="text-align:center;margin-top:0.5rem;">
                            <span style="font-family:'Audiowide',sans-serif;font-size:0.6rem;color:#e10600;letter-spacing:1px;">+30 PTS if correct</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ───────── COMMUNITY ───────── --}}
<section style="padding:6rem 1.5rem;">
    <div class="section-inner">
        <div style="text-align:center;margin-bottom:3.5rem;">
            <p class="section-eyebrow reveal">Community</p>
            <h2 class="section-title-text reveal">Race forums for every Grand Prix</h2>
            <p class="section-sub reveal" style="margin:0 auto;">Share your reactions, debate the stewards' decisions, and celebrate the wins with the F1Hub community.</p>
        </div>
        <div class="forum-mock reveal-scale">
            <div class="forum-header">
                <div class="forum-header-dot"></div>
                <div class="forum-header-title">Monaco Grand Prix — Community Forum</div>
            </div>
            <div class="forum-post">
                <div class="forum-avatar" style="background:linear-gradient(135deg,#e10600,#800000);">JD</div>
                <div>
                    <div class="forum-post-title">That overtake in lap 47 was absolutely insane 🔥</div>
                    <div class="forum-post-meta">posted by JDragon · 2 hours ago · 14 replies</div>
                </div>
                <div class="forum-post-likes">❤️ 42</div>
            </div>
            <div class="forum-post">
                <div class="forum-avatar" style="background:linear-gradient(135deg,#4781D7,#1e3a8a);">RK</div>
                <div>
                    <div class="forum-post-title">Stewards made the right call on the track limits penalty</div>
                    <div class="forum-post-meta">posted by RaceKing · 5 hours ago · 28 replies</div>
                </div>
                <div class="forum-post-likes">❤️ 17</div>
            </div>
            <div class="forum-post">
                <div class="forum-avatar" style="background:linear-gradient(135deg,#229971,#065f46);">MF</div>
                <div>
                    <div class="forum-post-title">Strategy call from the pit wall literally won them the race</div>
                    <div class="forum-post-meta">posted by MaxFan · 8 hours ago · 9 replies</div>
                </div>
                <div class="forum-post-likes">❤️ 31</div>
            </div>
            <div class="forum-post">
                <div class="forum-avatar" style="background:linear-gradient(135deg,#F47600,#92400e);">SP</div>
                <div>
                    <div class="forum-post-title">Thoughts on the safety car timing? Was it the right call?</div>
                    <div class="forum-post-meta">posted by SilverPro · 11 hours ago · 41 replies</div>
                </div>
                <div class="forum-post-likes">❤️ 58</div>
            </div>
        </div>
        <div style="text-align:center;margin-top:2.5rem;" class="reveal">
            <a href="{{ route('forums.index') }}" class="btn-ghost">Browse all forums</a>
        </div>
    </div>
</section>

{{-- ───────── CTA ───────── --}}
<section class="cta-section">
    <div class="cta-inner">
        <p class="section-eyebrow reveal" style="margin-bottom:1.25rem;">Ready to race?</p>
        <h2 class="section-title-text reveal" style="margin-bottom:1rem;font-size:clamp(2rem,5vw,4rem);">
            Join F1Hub today.<br>It's <span class="accent-glow">free.</span>
        </h2>
        <p class="section-sub reveal" style="margin:0 auto 2.5rem;font-size:1rem;">
            No paywalls, no ads — just pure Formula 1 data, community, and competition.
        </p>
        <div class="hero-cta-group reveal">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-primary">Go to Dashboard</a>
            @else
                <a href="{{ route('register') }}" class="btn-primary">Create Free Account</a>
                <a href="{{ route('login') }}" class="btn-ghost">Sign In</a>
            @endauth
        </div>
    </div>
</section>

{{-- ───────── FOOTER ───────── --}}
<div style="border-top:1px solid rgba(255,255,255,0.06);">
    <footer class="site-footer">
        <div class="footer-logo">F1<span>HUB</span></div>
        <div class="footer-links">
            <a href="{{ route('drivers.index') }}" class="footer-link">Drivers</a>
            <a href="{{ route('races.index') }}" class="footer-link">Races</a>
            <a href="{{ route('standings.index') }}" class="footer-link">Standings</a>
            <a href="{{ route('forums.index') }}" class="footer-link">Forums</a>
            @guest
                <a href="{{ route('login') }}" class="footer-link">Login</a>
                <a href="{{ route('register') }}" class="footer-link">Register</a>
            @endguest
        </div>
        <div class="footer-link">© {{ date('Y') }} F1Hub</div>
    </footer>
</div>

<script>
// Scroll reveal
(function(){
    const targets = document.querySelectorAll('.reveal, .reveal-scale, .reveal-left, .reveal-right');
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
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
    targets.forEach(el => io.observe(el));
})();

// Next race countdown
(function(){
    @if($nextRace)
    const raceDate = new Date("{{ $nextRace['date'] }}T{{ $nextRace['time'] ?? '00:00:00Z' }}").getTime();
    const ids = ['days-landing','hours-landing','minutes-landing','seconds-landing'];
    function tick() {
        const d = raceDate - Date.now();
        if (d <= 0) { ids.forEach(id => { const el = document.getElementById(id); if(el) el.textContent = '0'; }); return; }
        const vals = [
            Math.floor(d / 86400000),
            Math.floor((d % 86400000) / 3600000),
            Math.floor((d % 3600000) / 60000),
            Math.floor((d % 60000) / 1000),
        ];
        ids.forEach((id, i) => { const el = document.getElementById(id); if(el) el.textContent = vals[i]; });
    }
    tick();
    setInterval(tick, 1000);
    @endif
})();

// Hero bg parallax + load animation
(function(){
    const bg = document.getElementById('heroBg');
    if (!bg) return;
    bg.classList.add('loaded');
    window.addEventListener('scroll', () => {
        bg.style.transform = `scale(1) translateY(${window.scrollY * 0.35}px)`;
    }, { passive: true });
})();
</script>
</body>
</html>
