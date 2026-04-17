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
    .reveal-stagger.is-visible > *:nth-child(2)  { transition-delay: 0.05s; }
    .reveal-stagger.is-visible > *:nth-child(3)  { transition-delay: 0.10s; }
    .reveal-stagger.is-visible > *:nth-child(4)  { transition-delay: 0.15s; }
    .reveal-stagger.is-visible > *:nth-child(5)  { transition-delay: 0.20s; }
    .reveal-stagger.is-visible > *:nth-child(6)  { transition-delay: 0.25s; }
    .reveal-stagger.is-visible > *:nth-child(7)  { transition-delay: 0.30s; }
    .reveal-stagger.is-visible > *:nth-child(8)  { transition-delay: 0.35s; }
    .reveal-stagger.is-visible > *:nth-child(n+9) { transition-delay: 0.40s; }

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
    .standings-hero {
        position: relative;
        overflow: hidden;
        background:
            radial-gradient(ellipse at 50% 20%, rgba(225,6,0,0.22) 0%, transparent 60%),
            linear-gradient(135deg, #0a0a0f 0%, #15151e 60%, #0a0a0f 100%);
    }
    .standings-hero::before {
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

    .standings-hero h2 {
        letter-spacing: 4px;
        text-shadow: 0 0 30px rgba(225,6,0,0.35);
    }
    .standings-hero h2 .accent {
        color: #e10600;
        text-shadow: 0 0 20px rgba(225,6,0,0.8);
    }

    /* ── Season selector ─────────────────────────────────── */
    .season-select {
        background: rgba(255,255,255,0.08);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.25);
        color: white;
        padding: 0.65rem 1.25rem;
        border-radius: 9999px;
        font-weight: 600;
        letter-spacing: 1.5px;
        transition: border-color 0.25s ease, box-shadow 0.25s ease;
        cursor: pointer;
    }
    .season-select:hover,
    .season-select:focus {
        border-color: rgba(225,6,0,0.9);
        box-shadow: 0 0 15px rgba(225,6,0,0.5);
        outline: none;
    }
    .season-select option { background: #15151e; color: white; }

    /* ── Tabs: Drivers / Constructors ────────────────────── */
    .standings-tabs {
        display: inline-flex;
        gap: 0;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 9999px;
        padding: 4px;
    }
    .standings-tabs a {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1.5rem;
        border-radius: 9999px;
        font-weight: 700;
        letter-spacing: 1.5px;
        color: rgba(255,255,255,0.7);
        transition: all 0.25s ease;
        font-size: 0.875rem;
    }
    .standings-tabs a:hover { color: white; }
    .standings-tabs a.active {
        background: linear-gradient(90deg, #e10600, #a30400);
        color: white;
        box-shadow: 0 0 15px rgba(225,6,0,0.5);
    }

    /* ── Podium cards (top 3) ────────────────────────────── */
    .podium-card {
        position: relative;
        padding: 1.75rem 1.5rem;
        border-radius: 1rem;
        color: white;
        overflow: hidden;
        transition: transform 0.35s ease, box-shadow 0.35s ease;
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
    }
    .podium-card::before {
        content: "";
        position: absolute; inset: 0;
        background: linear-gradient(135deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.35) 100%);
        pointer-events: none;
    }
    .podium-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 0 30px rgba(225,6,0,0.4), 0 10px 30px rgba(0,0,0,0.6);
    }
    .podium-card .pos-medal {
        position: absolute;
        top: 1rem; right: 1rem;
        width: 3.25rem; height: 3.25rem;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.35rem;
        border: 2px solid rgba(255,255,255,0.35);
        z-index: 2;
    }
    .medal-1 { background: linear-gradient(135deg, #facc15 0%, #b45309 100%); color: #000; }
    .medal-2 { background: linear-gradient(135deg, #e5e7eb 0%, #6b7280 100%); color: #000; }
    .medal-3 { background: linear-gradient(135deg, #fb923c 0%, #9a3412 100%); color: #000; }

    /* ── Standings table ─────────────────────────────────── */
    .standings-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 6px;
    }
    .standings-table thead th {
        background: transparent;
        color: rgba(255,255,255,0.5);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 2px;
        padding: 0.75rem 1rem;
        text-align: left;
    }
    .standings-row {
        background: rgba(255,255,255,0.03);
        transition: background 0.25s ease, transform 0.25s ease;
    }
    .standings-row:hover {
        background: rgba(225,6,0,0.1);
    }
    .standings-row td {
        padding: 0.9rem 1rem;
        color: white;
        border-top: 1px solid rgba(255,255,255,0.06);
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .standings-row td:first-child {
        border-left: 1px solid rgba(255,255,255,0.06);
        border-top-left-radius: 0.5rem;
        border-bottom-left-radius: 0.5rem;
    }
    .standings-row td:last-child {
        border-right: 1px solid rgba(255,255,255,0.06);
        border-top-right-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
    }
    .pos-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.25rem; height: 2.25rem;
        border-radius: 9999px;
        font-weight: 800;
        font-size: 0.95rem;
        border: 2px solid rgba(255,255,255,0.15);
        background: rgba(0,0,0,0.35);
        color: white;
    }
    .pos-badge.pos-1 { background: linear-gradient(135deg, #facc15 0%, #b45309 100%); color: #000; border-color: rgba(250,204,21,0.6); }
    .pos-badge.pos-2 { background: linear-gradient(135deg, #e5e7eb 0%, #6b7280 100%); color: #000; border-color: rgba(229,231,235,0.6); }
    .pos-badge.pos-3 { background: linear-gradient(135deg, #fb923c 0%, #9a3412 100%); color: #000; border-color: rgba(251,146,60,0.6); }

    .team-color-stripe {
        display: inline-block;
        width: 4px;
        height: 1.75rem;
        border-radius: 2px;
        vertical-align: middle;
        margin-right: 0.75rem;
    }

    .points-cell {
        font-variant-numeric: tabular-nums;
        font-weight: 700;
        font-size: 1.1rem;
    }

    @media (prefers-reduced-motion: reduce) {
        .reveal, .reveal-scale, .reveal-stagger > * {
            opacity: 1 !important; transform: none !important; transition: none !important;
        }
    }
</style>
