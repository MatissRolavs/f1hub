<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'f1hub') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;700&display=swap" rel="stylesheet">
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <style>
        #ds-strip {
            position: fixed;
            top: 4rem;
            left: 0; right: 0;
            z-index: 40;
            background: #0a0a0f;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            padding: 0.45rem 1rem;
            opacity: 0;
            pointer-events: none;
            transform: translateY(-4px);
            transition: opacity 0.2s ease, transform 0.2s ease;
        }
        #ds-strip.ds-visible {
            opacity: 1;
            pointer-events: all;
            transform: translateY(0);
        }
        #ds-wrap { max-width: 80rem; margin: 0 auto; position: relative; }
        #ds-input-row {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 0.5rem;
            padding: 0.35rem 0.85rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        #ds-input-row:focus-within {
            border-color: rgba(225,6,0,0.5);
            box-shadow: 0 0 12px rgba(225,6,0,0.2);
        }
        #ds-input-row svg { flex-shrink: 0; opacity: 0.35; }
        #ds-input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            color: white;
            font-family: 'Audiowide', sans-serif;
            font-size: 0.7rem;
            letter-spacing: 1px;
        }
        #ds-input::placeholder { color: rgba(255,255,255,0.3); }
        #ds-clear {
            background: none; border: none;
            color: rgba(255,255,255,0.3);
            cursor: pointer; font-size: 1rem;
            line-height: 1; padding: 0; display: none;
        }
        #ds-clear:hover { color: #e10600; }
        #ds-results {
            display: none;
            position: absolute;
            top: calc(100% + 0.35rem);
            left: 0; right: 0;
            background: #0f0f17;
            border: 1px solid rgba(255,255,255,0.08);
            border-top: 2px solid #e10600;
            border-radius: 0 0 0.75rem 0.75rem;
            box-shadow: 0 12px 30px rgba(0,0,0,0.7);
            overflow: hidden;
            z-index: 100;
        }
        #ds-results.open { display: block; }
        .ds-item {
            display: flex; align-items: center; gap: 0.85rem;
            padding: 0.75rem 1rem;
            cursor: pointer; text-decoration: none;
            border-left: 3px solid transparent;
            transition: background 0.15s, border-color 0.15s;
        }
        .ds-item:hover { background: rgba(225,6,0,0.08); border-left-color: #e10600; }
        .ds-num {
            font-family: 'Audiowide', sans-serif;
            font-size: 1rem; font-weight: 700;
            min-width: 2.5rem; text-align: center; line-height: 1;
        }
        .ds-info { flex: 1; min-width: 0; }
        .ds-name { font-family: 'Audiowide', sans-serif; font-size: 0.72rem; letter-spacing: 1px; color: white; }
        .ds-team { font-size: 0.62rem; letter-spacing: 1.5px; text-transform: uppercase; color: rgba(255,255,255,0.35); margin-top: 0.15rem; }
        .ds-code { font-family: 'Audiowide', sans-serif; font-size: 0.65rem; letter-spacing: 2px; color: rgba(255,255,255,0.25); }
        .ds-empty { padding: 1rem; text-align: center; font-family: 'Audiowide', sans-serif; font-size: 0.65rem; letter-spacing: 1px; color: rgba(255,255,255,0.25); }
    </style>

    <body class="font-sans antialiased bg-gray-800">
        <div class="min-h-screen bg-gray-800">
            @include('layouts.navigation')

            <!-- Driver search strip -->
            <div id="ds-strip">
                <div id="ds-wrap">
                    <div id="ds-input-row">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                        </svg>
                        <input id="ds-input" type="text" placeholder="Search drivers…" autocomplete="off" spellcheck="false">
                        <button id="ds-clear" type="button" aria-label="Clear">✕</button>
                    </div>
                    <div id="ds-results"></div>
                </div>
            </div>

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="pt-16">
                {{ $slot }}
            </main>
        </div>

        <script>
        (function () {
            const input    = document.getElementById('ds-input');
            const results  = document.getElementById('ds-results');
            const clearBtn = document.getElementById('ds-clear');
            const strip    = document.getElementById('ds-strip');
            const navLink  = document.getElementById('drivers-nav-link');
            const searchUrl = '{{ route('drivers.search') }}';
            let debounce = null, lastQ = '', hideTimer = null;

            // ── Show / hide strip on drivers link hover ───────
            function showStrip() {
                clearTimeout(hideTimer);
                strip.classList.add('ds-visible');
            }
            function scheduleHide() {
                hideTimer = setTimeout(() => {
                    strip.classList.remove('ds-visible');
                    hideResults();
                }, 120);
            }

            navLink?.addEventListener('mouseenter', showStrip);
            navLink?.addEventListener('mouseleave', scheduleHide);
            strip?.addEventListener('mouseenter', showStrip);
            strip?.addEventListener('mouseleave', scheduleHide);

            function showResults(html) { results.innerHTML = html; results.classList.add('open'); }
            function hideResults() { results.classList.remove('open'); }

            function renderDrivers(drivers) {
                if (!drivers.length) { showResults('<div class="ds-empty">No drivers found</div>'); return; }
                showResults(drivers.map(d => `
                    <a class="ds-item" href="${d.url}">
                        <span class="ds-num" style="color:${d.color};">${d.number ?? '—'}</span>
                        <div class="ds-info">
                            <div class="ds-name">${d.name}</div>
                            <div class="ds-team">${d.team ?? ''}</div>
                        </div>
                        <span class="ds-code">${d.code ?? ''}</span>
                    </a>`).join(''));
            }

            input.addEventListener('input', function () {
                const q = this.value.trim();
                clearBtn.style.display = q ? 'block' : 'none';
                clearTimeout(debounce);
                if (q.length < 2) { hideResults(); lastQ = ''; return; }
                if (q === lastQ) return;
                debounce = setTimeout(async () => {
                    lastQ = q;
                    try {
                        const res  = await fetch(`${searchUrl}?q=${encodeURIComponent(q)}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                        const data = await res.json();
                        if (input.value.trim() === q) renderDrivers(data);
                    } catch (e) { console.error(e); }
                }, 220);
            });

            clearBtn.addEventListener('click', () => { input.value = ''; clearBtn.style.display = 'none'; hideResults(); input.focus(); });
            document.addEventListener('click', e => { if (!document.getElementById('ds-wrap').contains(e.target)) hideResults(); });

            input.addEventListener('keydown', e => {
                if (e.key === 'Escape') { hideResults(); input.blur(); }
                if (e.key === 'Enter') { const f = results.querySelector('.ds-item'); if (f) window.location.href = f.href; }
                if (e.key === 'ArrowDown') { const items = [...results.querySelectorAll('.ds-item')]; if (items[0]) items[0].focus(); }
            });
            results.addEventListener('keydown', e => {
                const items = [...results.querySelectorAll('.ds-item')];
                const idx = items.indexOf(document.activeElement);
                if (e.key === 'ArrowDown' && idx < items.length - 1) items[idx + 1].focus();
                if (e.key === 'ArrowUp') { if (idx > 0) items[idx - 1].focus(); else input.focus(); }
                if (e.key === 'Escape') { hideResults(); input.focus(); }
            });
        })();
        </script>
    </body>
</html>
