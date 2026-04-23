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

    /* Race selector */
    .race-select {
        margin-left: auto;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 0.5rem;
        color: white;
        font-family: 'Audiowide', sans-serif;
        font-size: 0.65rem;
        letter-spacing: 1px;
        padding: 0.5rem 0.85rem;
        outline: none;
        cursor: pointer;
        transition: border-color 0.2s;
        max-width: 240px;
    }
    .race-select:focus { border-color: rgba(225,6,0,0.6); }
    .race-select option { background: #1a1a28; }

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

    /* ── Video placeholder ──────────────────── */
    .video-slot {
        flex: 1;
        background: #0d0d14;
        border: 1px dashed rgba(255,255,255,0.1);
        border-radius: 0.75rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        color: rgba(255,255,255,0.2);
        font-family: 'Audiowide', sans-serif;
        font-size: 0.65rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        min-height: 0;
    }
    .video-slot svg { width: 40px; height: 40px; opacity: 0.2; }

    /* ── Chat panel — fixed width right column ── */
    .lc-panel {
        width: 340px;
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
    .lc-online {
        margin-left: auto;
        font-size: 0.6rem;
        color: rgba(255,255,255,0.25);
        font-family: 'Audiowide', sans-serif;
        letter-spacing: 1px;
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
        <select id="race-selector" class="race-select" aria-label="Select race">
            @foreach($races as $race)
                <option value="{{ $race->season }}_{{ $race->round }}"
                        data-name="{{ $race->name }}"
                        data-send-url="{{ route('races.chat.send', [$race->season, $race->round]) }}"
                        @selected($defaultRace && $race->id === $defaultRace->id)>
                    Rd {{ $race->round }} — {{ $race->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- ── Main row: video left, chat right ── --}}
    <div class="lc-content">

        {{-- Video side --}}
        <div class="lc-video-side">
            <div class="video-slot" id="video-slot">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                    <rect x="2" y="4" width="20" height="16" rx="2"/>
                    <polygon points="10,8 16,12 10,16" fill="currentColor" stroke="none"/>
                </svg>
                <span>Video stream coming soon</span>
            </div>
        </div>

    {{-- ── Chat panel ── --}}
    <div class="lc-panel">
        <div class="lc-panel-header">
            <span class="live-dot" style="width:7px;height:7px;"></span>
            <span class="lc-race-name" id="panel-race-name">
                {{ $defaultRace ? $defaultRace->name : 'Select a race' }}
            </span>
            <span class="lc-online" id="online-count"></span>
        </div>

        <div id="chat-messages">
            <div id="chat-empty">
                <div style="font-size:1.5rem;margin-bottom:0.5rem;">🏁</div>
                No messages yet — be the first to say something!
            </div>
        </div>

        @auth
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
// Blade data injected before Echo is ready — safe, just plain values
const CHAT_CONFIG = {
    @auth
    myName:    @json(auth()->user()->name),
    myColor:   @json($chatUserColor),
    loggedIn:  true,
    @else
    myName:    null,
    myColor:   '#e10600',
    loggedIn:  false,
    @endauth
    csrfToken: document.querySelector('meta[name="csrf-token"]')?.content ?? '',
};

// Wait for app.js module (which sets window.Echo) to finish executing,
// then boot the chat. 'load' fires after all deferred/module scripts run.
window.addEventListener('load', function () {
    const selector    = document.getElementById('race-selector');
    const panelName   = document.getElementById('panel-race-name');
    const list        = document.getElementById('chat-messages');
    const input       = document.getElementById('chat-input');
    const sendBtn     = document.getElementById('chat-send');

    let currentChannel = null;
    let currentSendUrl = null;

    // ── Helpers ──────────────────────────────────────────────────
    function getSelectedOption() {
        return selector?.options[selector.selectedIndex] ?? null;
    }

    function clearMessages() {
        list.innerHTML = `
            <div id="chat-empty" style="margin:auto;text-align:center;color:rgba(255,255,255,0.18);font-size:0.8rem;line-height:2;">
                <div style="font-size:1.5rem;margin-bottom:0.5rem;">🏁</div>
                No messages yet — be the first to say something!
            </div>`;
    }

    function appendMessage({ username, message, teamColor, timestamp }, own = false) {
        const empty = document.getElementById('chat-empty');
        if (empty) empty.remove();

        const wrap = document.createElement('div');
        wrap.className = 'chat-msg' + (own ? ' own' : '');

        wrap.innerHTML = `
            <div class="meta">
                <span class="dot" style="background:${teamColor};box-shadow:0 0 6px ${teamColor}99;"></span>
                <span class="uname" style="color:${teamColor};">${escHtml(username)}</span>
                <span class="ts">${escHtml(timestamp)}</span>
            </div>
            <div class="body">${escHtml(message)}</div>`;

        list.appendChild(wrap);
        list.scrollTop = list.scrollHeight;
    }

    function escHtml(str) {
        return String(str)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function nowTime() {
        return new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    // ── Switch race channel ───────────────────────────────────────
    function switchRace(raceKey, raceName, sendUrl) {
        // Leave previous channel
        if (currentChannel) {
            window.Echo.leave(`race-chat.${currentChannel}`);
        }

        currentChannel = raceKey;
        currentSendUrl = sendUrl;

        if (panelName) panelName.textContent = raceName;
        clearMessages();

        // Subscribe to new channel
        window.Echo.channel(`race-chat.${raceKey}`)
            .listen('.message', (data) => {
                appendMessage(data, false);
            });
    }

    // ── Send message ─────────────────────────────────────────────
    async function sendMessage() {
        if (!CHAT_CONFIG.loggedIn || !currentSendUrl) return;
        const msg = input.value.trim();
        if (!msg) return;

        input.value = '';
        if (sendBtn) sendBtn.disabled = true;

        // Optimistically render own message immediately
        appendMessage({
            username:  CHAT_CONFIG.myName,
            message:   msg,
            teamColor: CHAT_CONFIG.myColor,
            timestamp: nowTime(),
        }, true);

        try {
            await fetch(currentSendUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CHAT_CONFIG.csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-Socket-ID': window.Echo.socketId(),
                },
                body: JSON.stringify({ message: msg }),
            });
        } catch (err) {
            console.error('Chat send failed:', err);
        } finally {
            if (sendBtn) sendBtn.disabled = false;
            input.focus();
        }
    }

    // ── Event listeners ──────────────────────────────────────────
    selector?.addEventListener('change', () => {
        const opt = getSelectedOption();
        if (!opt) return;
        switchRace(opt.value, opt.dataset.name, opt.dataset.sendUrl);
    });

    sendBtn?.addEventListener('click', sendMessage);

    input?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    // ── Boot with default race ────────────────────────────────────
    const opt = getSelectedOption();
    if (opt) {
        switchRace(opt.value, opt.dataset.name, opt.dataset.sendUrl);
    }
});
</script>
</x-app-layout>
