<x-app-layout>
<style>
    body, html { background: #0a0a0f !important; overflow: hidden; height: 100%; }

    /* ── Outer wrapper — full viewport minus nav ── */
    .lc-wrap {
        height: calc(100vh - 4rem);
        display: flex;
        flex-direction: column;
        padding: 0.75rem 1rem;
        gap: 0.75rem;
        overflow: hidden;
    }

    /* ── Header bar ─────────────────────────── */
    .lc-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-shrink: 0;
    }
    .lc-title {
        font-family: 'Audiowide', sans-serif;
        font-size: 0.9rem;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: white;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }
    .live-dot {
        width: 9px; height: 9px;
        border-radius: 50%;
        background: #e10600;
        box-shadow: 0 0 10px rgba(225,6,0,0.9);
        animation: livepulse 1.8s ease-in-out infinite;
        flex-shrink: 0;
    }
    @keyframes livepulse {
        0%, 100% { opacity: 1; box-shadow: 0 0 10px rgba(225,6,0,0.9); }
        50%       { opacity: 0.35; box-shadow: 0 0 3px rgba(225,6,0,0.3); }
    }

    /* ── Main content row — video left, chat right ── */
    .lc-content {
        flex: 1;
        display: flex;
        gap: 0.75rem;
        min-height: 0;
    }

    /* ── Video side ─────────────────────────── */
    .lc-video-side {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-width: 0;
        gap: 0;
    }

    /* ── Mobile: hide placeholder, chat goes full width ── */
    @media (max-width: 639px) {
        .lc-video-side { display: none; }
        .lc-panel {
            width: 100%;
            flex: 1;
        }
    }

    /* ── Video / coming-soon slot ───────────── */
    .video-slot {
        flex: 1;
        border-radius: 0.75rem;
        overflow: hidden;
        min-height: 0;
        position: relative;
        background: linear-gradient(135deg, #0f0f17 0%, #1a1a28 100%);
        border: 1px solid rgba(255,255,255,0.07);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .coming-soon-inner {
        text-align: center;
        padding: 2rem;
        user-select: none;
    }
    .coming-soon-icon {
        font-size: 3.5rem;
        margin-bottom: 1.25rem;
        line-height: 1;
        filter: drop-shadow(0 0 18px rgba(225,6,0,0.55));
        animation: iconpulse 3s ease-in-out infinite;
    }
    @keyframes iconpulse {
        0%, 100% { transform: scale(1); filter: drop-shadow(0 0 18px rgba(225,6,0,0.55)); }
        50%       { transform: scale(1.06); filter: drop-shadow(0 0 30px rgba(225,6,0,0.9)); }
    }
    .coming-soon-title {
        font-family: 'Audiowide', sans-serif;
        font-size: 1.05rem;
        letter-spacing: 4px;
        text-transform: uppercase;
        color: white;
        margin-bottom: 0.6rem;
    }
    .coming-soon-sub {
        font-size: 0.78rem;
        letter-spacing: 1.5px;
        color: rgba(255,255,255,0.35);
        line-height: 1.7;
    }
    .coming-soon-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        margin-top: 1.5rem;
        padding: 0.4rem 1rem;
        border-radius: 9999px;
        border: 1px solid rgba(225,6,0,0.4);
        background: rgba(225,6,0,0.08);
        font-family: 'Audiowide', sans-serif;
        font-size: 0.6rem;
        letter-spacing: 2px;
        color: #fca5a5;
        text-transform: uppercase;
    }

    /* ── Chat panel — fixed width right column ── */
    .lc-panel {
        width: 420px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        background: #0f0f17;
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 1rem;
        overflow: hidden;
        min-height: 0;
    }
    .lc-panel-header {
        padding: 0.9rem 1.25rem;
        border-bottom: 1px solid rgba(255,255,255,0.07);
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }
    .lc-race-name {
        font-family: 'Audiowide', sans-serif;
        font-size: 0.65rem;
        letter-spacing: 1.5px;
        color: rgba(255,255,255,0.5);
    }
    /* Messages */
    #chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
        scroll-behavior: smooth;
    }
    #chat-messages::-webkit-scrollbar { width: 4px; }
    #chat-messages::-webkit-scrollbar-track { background: transparent; }
    #chat-messages::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 2px; }

    #chat-empty {
        margin: auto;
        text-align: center;
        color: rgba(255,255,255,0.18);
        font-size: 0.8rem;
        line-height: 2;
    }

    /* Individual messages */
    .chat-msg {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
        animation: chatIn 0.2s ease;
        position: relative;
    }
    @keyframes chatIn {
        from { opacity: 0; transform: translateY(6px); }
        to   { opacity: 1; transform: none; }
    }
    .chat-msg .meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .chat-msg .dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .chat-msg .uname {
        font-family: 'Audiowide', sans-serif;
        font-size: 0.62rem;
        letter-spacing: 1px;
    }
    .chat-msg .ts {
        font-size: 0.58rem;
        color: rgba(255,255,255,0.2);
        margin-left: auto;
    }

    /* Admin controls */
    .admin-menu {
        display: none;
        position: absolute;
        right: 0; top: 0;
        background: #1a1a2e;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 0.5rem;
        overflow: hidden;
        z-index: 50;
        min-width: 160px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.6);
    }
    .chat-msg:hover .admin-trigger { opacity: 1; }
    .admin-trigger {
        opacity: 0;
        transition: opacity 0.15s;
        background: none;
        border: none;
        color: rgba(255,255,255,0.35);
        cursor: pointer;
        font-size: 0.7rem;
        padding: 0 0.25rem;
        line-height: 1;
        margin-left: 0.25rem;
    }
    .admin-trigger:hover { color: #e10600; }
    .admin-menu.open { display: block; }
    .admin-menu button {
        display: block;
        width: 100%;
        text-align: left;
        background: none;
        border: none;
        color: rgba(255,255,255,0.7);
        font-size: 0.75rem;
        padding: 0.6rem 0.9rem;
        cursor: pointer;
        transition: background 0.15s, color 0.15s;
    }
    .admin-menu button:hover { background: rgba(225,6,0,0.15); color: #e10600; }
    .admin-menu button.unmute-btn { color: rgba(100,255,100,0.7); }
    .admin-menu button.unmute-btn:hover { background: rgba(100,255,100,0.1); color: #4ade80; }

    /* System messages (mute notifications) */
    .chat-system {
        text-align: center;
        font-size: 0.7rem;
        color: rgba(255,255,255,0.3);
        font-family: 'Audiowide', sans-serif;
        letter-spacing: 1px;
        padding: 0.25rem 0;
        animation: chatIn 0.2s ease;
    }
    .chat-system.warn { color: rgba(225,6,0,0.7); }

    /* Muted input state */
    #chat-input.muted, #chat-send.muted {
        opacity: 0.4;
        cursor: not-allowed;
        pointer-events: none;
    }
    .muted-notice {
        font-size: 0.7rem;
        color: rgba(225,6,0,0.7);
        text-align: center;
        padding: 0.4rem;
        font-family: 'Audiowide', sans-serif;
        letter-spacing: 1px;
        display: none;
    }

    .chat-msg .body {
        font-size: 0.9rem;
        color: rgba(255,255,255,0.8);
        padding-left: 1.25rem;
        line-height: 1.55;
        word-break: break-word;
    }
    .chat-msg.own .body { color: white; }

    /* ── Input row ──────────────────────────── */
    .lc-input-row {
        display: flex;
        gap: 0.65rem;
        padding: 0.9rem 1.25rem;
        border-top: 1px solid rgba(255,255,255,0.07);
        align-items: center;
    }
    #chat-input {
        flex: 1;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 0.5rem;
        padding: 0.65rem 1rem;
        color: white;
        font-size: 0.875rem;
        outline: none;
        transition: border-color 0.2s;
    }
    #chat-input:focus { border-color: rgba(225,6,0,0.55); }
    #chat-input::placeholder { color: rgba(255,255,255,0.25); }
    #chat-send {
        background: #e10600;
        border: none;
        border-radius: 0.5rem;
        padding: 0.65rem 1.25rem;
        color: white;
        font-family: 'Audiowide', sans-serif;
        font-size: 0.65rem;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        cursor: pointer;
        transition: background 0.2s, box-shadow 0.2s;
        white-space: nowrap;
    }
    #chat-send:hover { background: #c20500; box-shadow: 0 0 16px rgba(225,6,0,0.5); }
    #chat-send:disabled { opacity: 0.4; cursor: not-allowed; }

    .lc-login-prompt {
        padding: 0.9rem 1.25rem;
        border-top: 1px solid rgba(255,255,255,0.07);
        text-align: center;
        font-size: 0.8rem;
        color: rgba(255,255,255,0.3);
    }
    .lc-login-prompt a { color: #e10600; text-decoration: none; }
    .lc-login-prompt a:hover { text-decoration: underline; }
</style>

<div class="lc-wrap">

    {{-- ── Header ── --}}
    <div class="lc-header">
        <div class="lc-title">
            <span class="live-dot"></span>
            Live Race Chat
        </div>
    </div>

    {{-- ── Main row: video left, chat right ── --}}
    <div class="lc-content">

        {{-- Coming soon side --}}
        <div class="lc-video-side">
            <div class="video-slot" id="video-slot">
                <div class="coming-soon-inner">
                    <div class="coming-soon-icon">📡</div>
                    <p class="coming-soon-title">Live Streaming</p>
                    <p class="coming-soon-sub">
                        Live race broadcast will be<br>available here during race weekends.
                    </p>
                    <span class="coming-soon-badge">
                        <span class="live-dot" style="width:6px;height:6px;"></span>
                        Coming Soon
                    </span>
                </div>
            </div>
        </div>

    {{-- ── Chat panel ── --}}
    <div class="lc-panel">
        <div class="lc-panel-header">
            <span class="live-dot" style="width:7px;height:7px;"></span>
            <span class="lc-race-name" id="panel-race-name">
                {{ $defaultRace ? $defaultRace->name : 'Race Chat' }}
            </span>
        </div>

        <div id="chat-messages">
            <div id="chat-empty">
                <div style="font-size:1.5rem;margin-bottom:0.5rem;">🏁</div>
                No messages yet — be the first to say something!
            </div>
        </div>

        @auth
        <div class="muted-notice" id="muted-notice">🔇 You are muted</div>
        <div class="lc-input-row">
            <input id="chat-input" type="text" maxlength="300"
                   placeholder="Say something about the race…" autocomplete="off">
            <button id="chat-send" type="button">Send</button>
        </div>
        @else
        <div class="lc-login-prompt">
            <a href="{{ route('login') }}">Log in</a> to join the conversation
        </div>
        @endauth
    </div>{{-- end .lc-panel --}}

    </div>{{-- end .lc-content --}}

</div>{{-- end .lc-wrap --}}

<script>
const CHAT_CONFIG = {
    @auth
    myName:   @json(auth()->user()->name),
    myColor:  @json($chatUserColor),
    myId:     @json(auth()->user()->id),
    isAdmin:  @json(auth()->user()->role === 'admin'),
    loggedIn: true,
    @else
    myName:   null, myColor: '#e10600', myId: null, isAdmin: false, loggedIn: false,
    @endauth
    csrfToken:  document.querySelector('meta[name="csrf-token"]')?.content ?? '',
    muteUrl:    '{{ route('chat.mute') }}',
    unmuteUrl:  '{{ route('chat.unmute') }}',
};

window.addEventListener('load', function () {
    const panelName = document.getElementById('panel-race-name');
    const list      = document.getElementById('chat-messages');
    const input     = document.getElementById('chat-input');
    const sendBtn   = document.getElementById('chat-send');
    const mutedNote = document.getElementById('muted-notice');

    let currentChannel = null;
    let currentSendUrl = null;
    let isMuted = false;
    let openMenu = null;

    // ── Helpers ──────────────────────────────────────────────
    const escHtml = str => String(str)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');

    const nowTime = () => new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

    function setMuted(muted, reason = '') {
        isMuted = muted;
        if (!input || !sendBtn) return;
        input.classList.toggle('muted', muted);
        sendBtn.classList.toggle('muted', muted);
        if (mutedNote) {
            mutedNote.style.display = muted ? 'block' : 'none';
            mutedNote.textContent = muted ? ('🔇 ' + (reason || 'You are muted')) : '';
        }
    }

    function clearMessages() {
        list.innerHTML = `<div id="chat-empty" style="margin:auto;text-align:center;color:rgba(255,255,255,0.18);font-size:0.8rem;line-height:2;"><div style="font-size:1.5rem;margin-bottom:0.5rem;">🏁</div>No messages yet — be the first to say something!</div>`;
    }

    function appendSystem(text, warn = false) {
        const el = document.createElement('div');
        el.className = 'chat-system' + (warn ? ' warn' : '');
        el.textContent = text;
        list.appendChild(el);
        list.scrollTop = list.scrollHeight;
    }

    function appendMessage({ username, message, teamColor, timestamp }, own = false) {
        const empty = document.getElementById('chat-empty');
        if (empty) empty.remove();

        const wrap = document.createElement('div');
        wrap.className = 'chat-msg' + (own ? ' own' : '');
        wrap.dataset.username = username;

        let adminHtml = '';
        if (CHAT_CONFIG.isAdmin && !own) {
            adminHtml = `
                <button class="admin-trigger" title="Moderate" data-username="${escHtml(username)}">⚙</button>
                <div class="admin-menu" data-username="${escHtml(username)}">
                    <button data-action="timeout" data-seconds="300"  data-username="${escHtml(username)}">⏱ Timeout 5 min</button>
                    <button data-action="timeout" data-seconds="1800" data-username="${escHtml(username)}">⏱ Timeout 30 min</button>
                    <button data-action="timeout" data-seconds="3600" data-username="${escHtml(username)}">⏱ Timeout 1 hour</button>
                    <button data-action="mute"    data-seconds=""     data-username="${escHtml(username)}">🔇 Permanent mute</button>
                    <button data-action="unmute"  data-username="${escHtml(username)}" class="unmute-btn">✅ Unmute</button>
                </div>`;
        }

        wrap.innerHTML = `
            <div class="meta">
                <span class="dot" style="background:${teamColor};box-shadow:0 0 6px ${teamColor}99;"></span>
                <span class="uname" style="color:${teamColor};">${escHtml(username)}</span>
                ${adminHtml}
                <span class="ts">${escHtml(timestamp)}</span>
            </div>
            <div class="body">${escHtml(message)}</div>`;

        list.appendChild(wrap);
        list.scrollTop = list.scrollHeight;
    }

    // ── Admin menu toggle ─────────────────────────────────────
    document.addEventListener('click', async (e) => {
        // Toggle menu
        const trigger = e.target.closest('.admin-trigger');
        if (trigger) {
            e.stopPropagation();
            const menu = trigger.parentElement.querySelector('.admin-menu');
            if (!menu) return;
            if (openMenu && openMenu !== menu) openMenu.classList.remove('open');
            menu.classList.toggle('open');
            openMenu = menu.classList.contains('open') ? menu : null;
            return;
        }

        // Menu action
        const btn = e.target.closest('.admin-menu button');
        if (btn) {
            e.stopPropagation();
            const action   = btn.dataset.action;
            const username = btn.dataset.username;
            const seconds  = btn.dataset.seconds ? parseInt(btn.dataset.seconds) : null;

            btn.closest('.admin-menu').classList.remove('open');
            openMenu = null;

            const url = action === 'unmute' ? CHAT_CONFIG.unmuteUrl : CHAT_CONFIG.muteUrl;
            await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CHAT_CONFIG.csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ username, expires_in: action === 'unmute' ? null : seconds }),
            });
            return;
        }

        // Close menu on outside click
        if (openMenu) { openMenu.classList.remove('open'); openMenu = null; }
    });

    // ── Switch race ───────────────────────────────────────────
    function switchRace(raceKey, raceName, sendUrl) {
        if (currentChannel) window.Echo.leave(`race-chat.${currentChannel}`);
        currentChannel = raceKey;
        currentSendUrl = sendUrl;
        if (panelName) panelName.textContent = raceName;
        clearMessages();

        window.Echo.channel(`race-chat.${raceKey}`)
            .listen('.message', (data) => appendMessage(data, false));
    }

    // ── Moderation channel (all users listen) ─────────────────
    window.Echo.channel('chat-moderation')
        .listen('.moderation', (data) => {
            if (data.action === 'muted') {
                const label = data.expiresIn
                    ? `${Math.round(data.expiresIn / 60)} min timeout`
                    : 'permanent mute';
                appendSystem(`${data.username} was given a ${label}.`, true);

                // Lock input if it's the current user
                if (CHAT_CONFIG.myId === data.userId) {
                    setMuted(true, data.expiresIn
                        ? `You are timed out for ${Math.round(data.expiresIn / 60)} min.`
                        : 'You are permanently muted.');

                    // Auto-unlock after timeout
                    if (data.expiresIn) {
                        setTimeout(() => setMuted(false), data.expiresIn * 1000);
                    }
                }
            } else if (data.action === 'unmuted') {
                appendSystem(`${data.username} was unmuted.`);
                if (CHAT_CONFIG.myId === data.userId) setMuted(false);
            }
        });

    // ── Send message ──────────────────────────────────────────
    async function sendMessage() {
        if (!CHAT_CONFIG.loggedIn || !currentSendUrl || isMuted) return;
        const msg = input.value.trim();
        if (!msg) return;

        input.value = '';
        if (sendBtn) sendBtn.disabled = true;

        appendMessage({
            username: CHAT_CONFIG.myName, message: msg,
            teamColor: CHAT_CONFIG.myColor, timestamp: nowTime(),
        }, true);

        try {
            const res = await fetch(currentSendUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CHAT_CONFIG.csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-Socket-ID': window.Echo.socketId(),
                },
                body: JSON.stringify({ message: msg }),
            });
            if (res.status === 403) {
                const data = await res.json();
                setMuted(true, data.message);
            }
        } catch (err) {
            console.error('Chat send failed:', err);
        } finally {
            if (sendBtn) sendBtn.disabled = false;
            input.focus();
        }
    }

    // ── Event listeners ───────────────────────────────────────
    sendBtn?.addEventListener('click', sendMessage);
    input?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
    });

    // ── Boot ──────────────────────────────────────────────────
    @if($defaultRace)
    switchRace(
        @json($defaultRace->season . '_' . $defaultRace->round),
        @json($defaultRace->name),
        @json(route('races.chat.send', [$defaultRace->season, $defaultRace->round]))
    );
    @endif
});
</script>
</x-app-layout>
