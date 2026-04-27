<x-app-layout>
<style>
    .reveal, .reveal-scale {
        opacity: 0;
        transition: opacity 0.9s cubic-bezier(.2,.65,.3,1), transform 0.9s cubic-bezier(.2,.65,.3,1);
        will-change: opacity, transform;
    }
    .reveal       { transform: translateY(40px); }
    .reveal-scale { transform: scale(0.96); }
    .is-visible   { opacity: 1 !important; transform: none !important; }
    .reveal-stagger > * { opacity: 0; transform: translateY(30px); transition: opacity 0.7s cubic-bezier(.2,.65,.3,1), transform 0.7s cubic-bezier(.2,.65,.3,1); }
    .reveal-stagger.is-visible > * { opacity: 1; transform: none; }
    .reveal-stagger.is-visible > *:nth-child(1) { transition-delay: 0s; }
    .reveal-stagger.is-visible > *:nth-child(2) { transition-delay: 0.08s; }
    .reveal-stagger.is-visible > *:nth-child(3) { transition-delay: 0.16s; }

    .section-title { position: relative; padding-left: 1.25rem; display: inline-block; }
    .section-title::before { content: ""; position: absolute; left: 0; top: 8%; bottom: 8%; width: 6px; background: #e10600; box-shadow: 0 0 12px rgba(225,6,0,0.6); }

    .admin-hero {
        position: relative; overflow: hidden;
        background: radial-gradient(ellipse at 50% 0%, rgba(225,6,0,0.3) 0%, transparent 55%), linear-gradient(180deg, #0a0a0f 0%, #15151e 100%);
    }
    .admin-hero::before { content: ""; position: absolute; inset: 0; background-image: repeating-linear-gradient(0deg, rgba(255,255,255,0.02) 0 1px, transparent 1px 3px); pointer-events: none; }
    .hero-stripe { position: absolute; left: 0; height: 2px; width: 100%; background: linear-gradient(90deg, transparent 0%, #e10600 30%, #e10600 70%, transparent 100%); box-shadow: 0 0 15px rgba(225,6,0,0.8); z-index: 3; }
    .hero-stripe.top { top: 0; }
    .hero-stripe.bottom { bottom: 0; }
    .admin-hero h2 { letter-spacing: 4px; text-shadow: 0 0 30px rgba(225,6,0,0.35); }
    .admin-hero h2 .accent { color: #e10600; text-shadow: 0 0 20px rgba(225,6,0,0.8); }

    /* ── Sync card ── */
    .sync-card {
        position: relative; display: flex; align-items: center; gap: 1.25rem;
        padding: 1.5rem 1.75rem;
        background: linear-gradient(135deg, #1a1a28 0%, #0f0f15 100%);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 1rem; overflow: hidden;
        cursor: pointer;
        transition: transform 0.35s ease, border-color 0.35s ease, box-shadow 0.35s ease;
        width: 100%; text-align: left;
    }
    .sync-card::before { content: ""; position: absolute; top: 0; left: 0; bottom: 0; width: 3px; background: var(--accent, #e10600); box-shadow: 0 0 10px var(--accent, rgba(225,6,0,0.6)); }
    .sync-card::after  { content: ""; position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent 0%, var(--accent, #e10600) 50%, transparent 100%); opacity: 0; transition: opacity 0.3s ease; }
    .sync-card:hover   { transform: translateX(4px); border-color: var(--accent-border, rgba(225,6,0,0.45)); box-shadow: 0 0 25px var(--accent-glow, rgba(225,6,0,0.35)); }
    .sync-card:hover::after { opacity: 0.8; }
    .sync-card:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

    .sync-icon {
        display: inline-flex; align-items: center; justify-content: center;
        width: 3rem; height: 3rem; border-radius: 0.75rem;
        background: rgba(var(--accent-rgb, 225,6,0), 0.12);
        border: 1px solid rgba(var(--accent-rgb, 225,6,0), 0.45);
        color: var(--accent, #fca5a5); flex-shrink: 0;
        transition: background 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
    }
    .sync-card:hover .sync-icon { background: var(--accent, #e10600); color: white; box-shadow: 0 0 18px rgba(var(--accent-rgb, 225,6,0), 0.65); }
    .sync-card h3 { font-size: 1.125rem; font-weight: 800; color: white; letter-spacing: 1.5px; text-transform: uppercase; }
    .sync-card p  { font-size: 0.8rem; color: rgba(255,255,255,0.55); margin-top: 0.25rem; }

    .sync-tag {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.2rem 0.65rem; border-radius: 9999px;
        background: rgba(var(--accent-rgb, 225,6,0), 0.1);
        border: 1px solid rgba(var(--accent-rgb, 225,6,0), 0.35);
        font-family: "IBM Plex Mono", monospace;
        font-size: 0.6rem; letter-spacing: 2px; color: var(--accent, #fca5a5); text-transform: uppercase;
    }
    .sync-arrow { color: rgba(255,255,255,0.4); transition: color 0.25s ease, transform 0.25s ease; flex-shrink: 0; }
    .sync-card:hover .sync-arrow { color: var(--accent, #fca5a5); transform: translateX(4px); }

    .back-link {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.5rem 1.25rem; border-radius: 9999px;
        background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.2);
        color: rgba(255,255,255,0.75); font-size: 0.75rem; letter-spacing: 2px; text-transform: uppercase;
        transition: all 0.25s ease;
    }
    .back-link:hover { border-color: rgba(255,255,255,0.5); color: white; background: rgba(255,255,255,0.08); }

    /* ── Progress overlay ── */
    #sync-overlay {
        display: none;
        position: fixed; inset: 0; z-index: 200;
        background: rgba(0,0,0,0.85);
        backdrop-filter: blur(8px);
        align-items: center; justify-content: center;
    }
    #sync-overlay.open { display: flex; }

    .sync-modal {
        background: #0f0f17;
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 1.25rem;
        padding: 2.5rem 2.75rem;
        width: 100%; max-width: 500px;
        box-shadow: 0 30px 80px rgba(0,0,0,0.9);
        position: relative; overflow: hidden;
    }
    .sync-modal::before {
        content: "";
        position: absolute; top: 0; left: 0; right: 0; height: 2px;
        background: var(--modal-accent, #e10600);
        box-shadow: 0 0 20px var(--modal-accent, rgba(225,6,0,0.8));
    }

    .sync-modal-icon {
        display: flex; align-items: center; justify-content: center;
        width: 3.5rem; height: 3.5rem; border-radius: 1rem; margin: 0 auto 1.5rem;
        background: rgba(var(--modal-accent-rgb, 225,6,0), 0.12);
        border: 1px solid rgba(var(--modal-accent-rgb, 225,6,0), 0.4);
    }
    .sync-modal-icon svg { width: 1.75rem; height: 1.75rem; color: var(--modal-accent, #fca5a5); }

    /* Spinning icon when running */
    .sync-modal-icon.spinning svg { animation: spinAnim 1s linear infinite; }
    @keyframes spinAnim { to { transform: rotate(360deg); } }

    .sync-modal-title {
        font-family: 'Audiowide', sans-serif;
        font-size: 0.75rem; letter-spacing: 3px; text-transform: uppercase;
        color: white; text-align: center; margin-bottom: 0.35rem;
    }
    .sync-modal-sub {
        font-size: 0.8rem; color: rgba(255,255,255,0.4);
        text-align: center; margin-bottom: 2rem;
        font-family: 'Audiowide', sans-serif; letter-spacing: 1px;
    }

    /* Progress bar track */
    .progress-track {
        height: 6px; background: rgba(255,255,255,0.07);
        border-radius: 9999px; overflow: hidden; margin-bottom: 0.75rem;
    }
    .progress-fill {
        height: 100%; border-radius: 9999px;
        background: var(--modal-accent, #e10600);
        box-shadow: 0 0 12px var(--modal-accent, rgba(225,6,0,0.6));
        transition: width 0.4s cubic-bezier(.4,0,.2,1);
        width: 0%;
    }

    .progress-row {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 1.5rem;
    }
    .progress-status {
        font-family: 'Audiowide', sans-serif;
        font-size: 0.62rem; letter-spacing: 1.5px; color: rgba(255,255,255,0.45);
    }
    .progress-pct {
        font-family: 'Audiowide', sans-serif;
        font-size: 0.8rem; letter-spacing: 1px; color: white; font-weight: 700;
    }

    /* Stage dots */
    .stage-list { display: flex; flex-direction: column; gap: 0.5rem; }
    .stage-item {
        display: flex; align-items: center; gap: 0.75rem;
        font-family: 'Audiowide', sans-serif; font-size: 0.6rem;
        letter-spacing: 1.5px; text-transform: uppercase;
        color: rgba(255,255,255,0.25);
        transition: color 0.3s;
    }
    .stage-item.done    { color: rgba(255,255,255,0.75); }
    .stage-item.active  { color: white; }
    .stage-dot {
        width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0;
        background: rgba(255,255,255,0.12);
        transition: background 0.3s, box-shadow 0.3s;
    }
    .stage-item.done   .stage-dot { background: var(--modal-accent, #e10600); }
    .stage-item.active .stage-dot {
        background: var(--modal-accent, #e10600);
        box-shadow: 0 0 8px var(--modal-accent, rgba(225,6,0,0.8));
        animation: pulseDot 1s ease-in-out infinite;
    }
    @keyframes pulseDot {
        0%,100% { opacity: 1; } 50% { opacity: 0.4; }
    }

    /* Result state */
    .sync-result { display: none; text-align: center; padding-top: 1rem; }
    .sync-result.show { display: block; }
    .sync-result-icon { font-size: 2.5rem; margin-bottom: 0.75rem; }
    .sync-result-msg { font-family: 'Audiowide', sans-serif; font-size: 0.7rem; letter-spacing: 2px; }
    .sync-result-msg.ok  { color: #4ade80; }
    .sync-result-msg.err { color: #fca5a5; }

    .sync-close-btn {
        display: none; width: 100%; margin-top: 1.5rem;
        padding: 0.75rem; border: none; border-radius: 0.6rem;
        background: rgba(255,255,255,0.06); color: white; cursor: pointer;
        font-family: 'Audiowide', sans-serif; font-size: 0.65rem; letter-spacing: 2px; text-transform: uppercase;
        transition: background 0.2s;
    }
    .sync-close-btn:hover { background: rgba(255,255,255,0.12); }
    .sync-close-btn.show { display: block; }

    @media (prefers-reduced-motion: reduce) {
        .reveal, .reveal-scale, .reveal-stagger > * { opacity: 1 !important; transform: none !important; transition: none !important; }
    }
</style>

{{-- Hero --}}
<section class="admin-hero text-white">
    <div class="hero-stripe top"></div>
    <div class="hero-stripe bottom"></div>
    <div class="relative z-10 max-w-5xl mx-auto px-4 py-14">
        <div class="reveal-scale text-center">
            <div class="mb-4">
                <a href="{{ route('admin.panel') }}" class="back-link">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Control Center
                </a>
            </div>
            <p class="audiowide-regular text-xs md:text-sm text-white/60 tracking-[6px] mb-2">DATA OPS CONSOLE</p>
            <h2 class="audiowide-regular text-4xl md:text-5xl lg:text-6xl font-bold mb-4">
                Sync <span class="accent">Panel</span>
            </h2>
            <p class="text-gray-300 max-w-2xl mx-auto text-base md:text-lg">
                Trigger one-click data sync from the Jolpica F1 API. Past seasons are skipped automatically.
            </p>
        </div>
    </div>
</section>

{{-- Actions --}}
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-white audiowide-regular">

    <h3 class="section-title text-lg md:text-xl font-bold uppercase mb-6 reveal">Available Operations</h3>

    <div class="reveal-stagger space-y-4">

        {{-- Race Results --}}
        <button type="button" class="sync-card"
                style="--accent:#3b82f6;--accent-rgb:59,130,246;--accent-border:rgba(59,130,246,0.5);--accent-glow:rgba(59,130,246,0.35);"
                data-sync-url="{{ route('results.index') }}"
                data-sync-title="Sync Season Race Results"
                data-sync-accent="#3b82f6"
                data-sync-accent-rgb="59,130,246"
                data-sync-stages='["Connecting to Jolpica API","Fetching historical seasons","Pulling race results","Processing lap data","Writing to database","Finalising"]'>
            <div class="sync-icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3 flex-wrap">
                    <h3>Sync Season Race Results</h3>
                    <span class="sync-tag">ALL SEASONS</span>
                </div>
                <p>Pulls every historical race result. Skips already-synced past seasons; always refreshes the current season.</p>
            </div>
            <svg class="sync-arrow w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        {{-- Season Races --}}
        <button type="button" class="sync-card"
                style="--accent:#22c55e;--accent-rgb:34,197,94;--accent-border:rgba(34,197,94,0.5);--accent-glow:rgba(34,197,94,0.35);"
                data-sync-url="{{ route('races.sync') }}"
                data-sync-title="Sync Current Season Races"
                data-sync-accent="#22c55e"
                data-sync-accent-rgb="34,197,94"
                data-sync-stages='["Connecting to Jolpica API","Fetching race calendar","Comparing with database","Syncing new rounds","Pruning orphaned rounds","Finalising"]'>
            <div class="sync-icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3 flex-wrap">
                    <h3>Sync Current Season Races</h3>
                    <span class="sync-tag">CALENDAR</span>
                </div>
                <p>Refreshes the race calendar from Jolpica. Skips seasons already on file, prunes orphaned rounds.</p>
            </div>
            <svg class="sync-arrow w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        {{-- Driver Standings --}}
        <button type="button" class="sync-card"
                style="--accent:#facc15;--accent-rgb:250,204,21;--accent-border:rgba(250,204,21,0.5);--accent-glow:rgba(250,204,21,0.35);"
                data-sync-url="{{ route('drivers.sync') }}"
                data-sync-title="Sync Driver Standings"
                data-sync-accent="#facc15"
                data-sync-accent-rgb="250,204,21"
                data-sync-stages='["Connecting to Jolpica API","Fetching standings data","Resolving driver records","Updating points & wins","Writing to database","Finalising"]'>
            <div class="sync-icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3 flex-wrap">
                    <h3>Sync Driver Standings</h3>
                    <span class="sync-tag">STANDINGS</span>
                </div>
                <p>Updates the live driver championship table with the latest points and wins.</p>
            </div>
            <svg class="sync-arrow w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

    </div>
</div>

{{-- ── Progress Overlay ── --}}
<div id="sync-overlay">
    <div class="sync-modal" id="sync-modal">

        <div class="sync-modal-icon" id="modal-icon">
            <svg id="modal-icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0011.667 0l3.181-3.183m-3.181-3.182a8.25 8.25 0 00-11.667 0l-3.181 3.182"/>
            </svg>
        </div>

        {{-- Running state --}}
        <div id="modal-running">
            <p class="sync-modal-title" id="modal-title"></p>
            <p class="sync-modal-sub" id="modal-sub">Initialising sync operation…</p>

            <div class="progress-track">
                <div class="progress-fill" id="progress-fill"></div>
            </div>
            <div class="progress-row">
                <span class="progress-status" id="progress-status">Starting…</span>
                <span class="progress-pct" id="progress-pct">0%</span>
            </div>

            <div class="stage-list" id="stage-list"></div>
        </div>

        {{-- Result state --}}
        <div class="sync-result" id="modal-result">
            <div class="sync-result-icon" id="result-icon"></div>
            <p class="sync-result-msg" id="result-msg"></p>
        </div>

        <button type="button" class="sync-close-btn" id="modal-close" onclick="closeOverlay()">
            Close
        </button>
    </div>
</div>

<script>
(function(){
    // ── Reveal observer ────────────────────────────────────────
    const targets = document.querySelectorAll('.reveal, .reveal-scale, .reveal-stagger');
    if ('IntersectionObserver' in window && targets.length) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('is-visible'); io.unobserve(e.target); } });
        }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
        targets.forEach(el => io.observe(el));
    } else {
        targets.forEach(el => el.classList.add('is-visible'));
    }

    // ── Sync logic ────────────────────────────────────────────
    const overlay    = document.getElementById('sync-overlay');
    const modal      = document.getElementById('sync-modal');
    const modalIcon  = document.getElementById('modal-icon');
    const modalTitle = document.getElementById('modal-title');
    const modalSub   = document.getElementById('modal-sub');
    const fill       = document.getElementById('progress-fill');
    const statusEl   = document.getElementById('progress-status');
    const pctEl      = document.getElementById('progress-pct');
    const stageList  = document.getElementById('stage-list');
    const running    = document.getElementById('modal-running');
    const result     = document.getElementById('modal-result');
    const closeBtn   = document.getElementById('modal-close');
    const resultIcon = document.getElementById('result-icon');
    const resultMsg  = document.getElementById('result-msg');

    let stageTimer = null;
    let currentPct = 0;

    function setProgress(pct) {
        currentPct = pct;
        fill.style.width = pct + '%';
        pctEl.textContent = Math.round(pct) + '%';
    }

    function buildStages(stages) {
        stageList.innerHTML = stages.map((s, i) => `
            <div class="stage-item" id="stage-${i}">
                <span class="stage-dot"></span>
                <span>${s}</span>
            </div>`).join('');
    }

    function activateStage(index, total) {
        document.querySelectorAll('.stage-item').forEach((el, i) => {
            el.classList.remove('active', 'done');
            if (i < index)  el.classList.add('done');
            if (i === index) el.classList.add('active');
        });
        const stage = document.getElementById('stage-' + index);
        if (stage) statusEl.textContent = stage.querySelector('span:last-child').textContent;
    }

    function runStages(stages, durationMs) {
        const interval = durationMs / stages.length;
        const pctPerStage = 88 / stages.length; // go up to 88%, last 12% when request completes
        let idx = 0;

        activateStage(0, stages.length);
        setProgress(0);

        stageTimer = setInterval(() => {
            idx++;
            if (idx >= stages.length) { clearInterval(stageTimer); return; }
            activateStage(idx, stages.length);
            setProgress(Math.min(88, pctPerStage * (idx + 1)));
        }, interval);
    }

    function showResult(ok, message) {
        clearInterval(stageTimer);

        // Animate to 100%
        setProgress(100);
        document.querySelectorAll('.stage-item').forEach(el => { el.classList.remove('active'); el.classList.add('done'); });
        modalIcon.classList.remove('spinning');

        setTimeout(() => {
            running.style.display = 'none';
            result.classList.add('show');
            resultIcon.textContent = ok ? '✅' : '❌';
            resultMsg.textContent  = ok ? 'Sync completed successfully.' : ('Error: ' + message);
            resultMsg.className    = 'sync-result-msg ' + (ok ? 'ok' : 'err');
            closeBtn.classList.add('show');
        }, 500);
    }

    window.closeOverlay = function() {
        overlay.classList.remove('open');
        clearInterval(stageTimer);
        // re-enable buttons
        document.querySelectorAll('.sync-card').forEach(b => b.disabled = false);
    };

    // Wire up sync buttons
    document.querySelectorAll('.sync-card').forEach(btn => {
        btn.addEventListener('click', async function() {
            const url    = this.dataset.syncUrl;
            const title  = this.dataset.syncTitle;
            const accent = this.dataset.syncAccent;
            const rgb    = this.dataset.syncAccentRgb;
            const stages = JSON.parse(this.dataset.syncStages);

            // Estimate duration based on operation (race results takes longest)
            const estimatedMs = url.includes('results') ? 12000 : url.includes('races') ? 5000 : 4000;

            // Set modal accent colour
            modal.style.setProperty('--modal-accent', accent);
            modal.style.setProperty('--modal-accent-rgb', rgb);
            fill.style.background = accent;
            fill.style.boxShadow  = `0 0 12px ${accent}99`;

            // Reset modal state
            running.style.display = '';
            result.classList.remove('show');
            closeBtn.classList.remove('show');
            modalIcon.classList.add('spinning');
            modalTitle.textContent = title;
            modalSub.textContent   = 'Communicating with Jolpica F1 API…';
            buildStages(stages);
            setProgress(0);

            // Disable all buttons while running
            document.querySelectorAll('.sync-card').forEach(b => b.disabled = true);

            overlay.classList.add('open');
            runStages(stages, estimatedMs);

            try {
                const res = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    redirect: 'follow',
                });
                showResult(res.ok, res.statusText);
            } catch (err) {
                showResult(false, err.message);
            }
        });
    });
})();
</script>
</x-app-layout>
