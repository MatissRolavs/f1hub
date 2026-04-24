<x-app-layout>
<style>
    .admin-hero {
        position: relative;
        overflow: hidden;
        background:
            radial-gradient(ellipse at 50% 0%, rgba(225,6,0,0.3) 0%, transparent 55%),
            linear-gradient(180deg, #0a0a0f 0%, #15151e 100%);
    }
    .admin-hero::before {
        content: "";
        position: absolute; inset: 0;
        background-image: repeating-linear-gradient(45deg, rgba(225,6,0,0.02) 0 14px, transparent 14px 28px);
        pointer-events: none;
    }
    .hero-stripe { position: absolute; left: 0; height: 2px; width: 100%; background: linear-gradient(90deg, transparent 0%, #e10600 30%, #e10600 70%, transparent 100%); box-shadow: 0 0 15px rgba(225,6,0,0.8); z-index: 3; }
    .hero-stripe.top { top: 0; }
    .hero-stripe.bottom { bottom: 0; }

    .section-title { position: relative; padding-left: 1.25rem; display: inline-block; }
    .section-title::before { content: ""; position: absolute; left: 0; top: 8%; bottom: 8%; width: 6px; background: #e10600; box-shadow: 0 0 12px rgba(225,6,0,0.6); }

    /* Filter bar */
    .filter-input, .filter-select {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.15);
        color: white;
        padding: 0.6rem 1rem;
        border-radius: 9999px;
        font-family: 'Audiowide', sans-serif;
        font-size: 0.7rem;
        letter-spacing: 1px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .filter-input::placeholder { color: rgba(255,255,255,0.35); }
    .filter-input:focus, .filter-select:focus { border-color: rgba(225,6,0,0.7); box-shadow: 0 0 12px rgba(225,6,0,0.25); }
    .filter-select option { background: #15151e; }

    .btn-f1 {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.6rem 1.25rem;
        background: linear-gradient(90deg, #e10600 0%, #a30400 100%);
        border-radius: 9999px;
        font-family: 'Audiowide', sans-serif;
        font-size: 0.65rem; letter-spacing: 1.5px; text-transform: uppercase;
        color: white; font-weight: 700;
        transition: transform 0.2s, box-shadow 0.2s;
        border: none; cursor: pointer;
    }
    .btn-f1:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(225,6,0,0.5); }

    /* User table */
    .user-table {
        width: 100%;
        border-collapse: collapse;
    }
    .user-table th {
        font-family: 'Audiowide', sans-serif;
        font-size: 0.6rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: rgba(255,255,255,0.35);
        padding: 0.75rem 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .user-table td {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        vertical-align: middle;
        color: rgba(255,255,255,0.85);
        font-size: 0.85rem;
    }
    .user-table tr:hover td { background: rgba(255,255,255,0.02); }
    .user-table tr:last-child td { border-bottom: none; }

    /* Role badge */
    .role-badge {
        display: inline-flex; align-items: center;
        padding: 0.2rem 0.6rem;
        border-radius: 9999px;
        font-family: 'Audiowide', sans-serif;
        font-size: 0.58rem; letter-spacing: 1.5px; text-transform: uppercase;
    }
    .role-badge.admin { background: rgba(225,6,0,0.15); color: #fca5a5; border: 1px solid rgba(225,6,0,0.4); }
    .role-badge.user  { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.5); border: 1px solid rgba(255,255,255,0.12); }

    /* Action buttons */
    .action-btn {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.35rem 0.75rem;
        border-radius: 0.4rem;
        font-family: 'Audiowide', sans-serif;
        font-size: 0.58rem; letter-spacing: 1px; text-transform: uppercase;
        border: 1px solid; cursor: pointer; background: none;
        transition: background 0.15s, box-shadow 0.15s;
    }
    .action-btn.role-btn { color: rgba(255,255,255,0.6); border-color: rgba(255,255,255,0.15); }
    .action-btn.role-btn:hover { background: rgba(255,255,255,0.08); color: white; }
    .action-btn.pw-btn { color: #60a5fa; border-color: rgba(96,165,250,0.3); }
    .action-btn.pw-btn:hover { background: rgba(96,165,250,0.1); }
    .action-btn.del-btn { color: #fca5a5; border-color: rgba(225,6,0,0.35); }
    .action-btn.del-btn:hover { background: rgba(225,6,0,0.15); box-shadow: 0 0 10px rgba(225,6,0,0.2); }

    /* Modal */
    .modal-backdrop {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.75);
        z-index: 100;
        align-items: center; justify-content: center;
    }
    .modal-backdrop.open { display: flex; }
    .modal-box {
        background: #0f0f17;
        border: 1px solid rgba(255,255,255,0.1);
        border-top: 2px solid #e10600;
        border-radius: 1rem;
        padding: 1.75rem;
        width: 100%;
        max-width: 420px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.8);
    }
    .modal-title { font-family: 'Audiowide', sans-serif; font-size: 0.85rem; letter-spacing: 2px; text-transform: uppercase; color: white; margin-bottom: 1rem; }
    .modal-input {
        width: 100%;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 0.5rem;
        padding: 0.65rem 1rem;
        color: white;
        font-size: 0.85rem;
        outline: none;
        margin-bottom: 0.75rem;
        transition: border-color 0.2s;
    }
    .modal-input:focus { border-color: rgba(225,6,0,0.5); }
    .modal-actions { display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1.25rem; }
    .modal-cancel { padding: 0.55rem 1.1rem; background: none; border: 1px solid rgba(255,255,255,0.15); border-radius: 0.4rem; color: rgba(255,255,255,0.6); cursor: pointer; font-size: 0.8rem; }
    .modal-cancel:hover { background: rgba(255,255,255,0.06); }

    /* Pagination */
    .f1-pagination { display: flex; align-items: center; justify-content: center; gap: 0.4rem; flex-wrap: wrap; }
    .f1-pagination a, .f1-pagination span {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 2.25rem; height: 2.25rem; padding: 0 0.6rem;
        border-radius: 0.4rem;
        font-family: 'Audiowide', sans-serif; font-size: 0.65rem; letter-spacing: 1px;
        border: 1px solid rgba(255,255,255,0.1);
        background: rgba(255,255,255,0.04);
        color: rgba(255,255,255,0.6);
        transition: background 0.2s, border-color 0.2s;
        text-decoration: none;
    }
    .f1-pagination a:hover { background: rgba(225,6,0,0.15); border-color: rgba(225,6,0,0.5); color: white; }
    .f1-pagination span.active { background: #e10600; border-color: #e10600; color: white; box-shadow: 0 0 14px rgba(225,6,0,0.5); }
    .f1-pagination span.disabled { opacity: 0.3; cursor: not-allowed; }

    /* Success / error flash */
    .flash { padding: 0.75rem 1.25rem; border-radius: 0.5rem; font-family: 'Audiowide', sans-serif; font-size: 0.7rem; letter-spacing: 1px; margin-bottom: 1.5rem; }
    .flash.success { background: rgba(74,222,128,0.1); border: 1px solid rgba(74,222,128,0.3); color: #4ade80; }
    .flash.error   { background: rgba(225,6,0,0.1);   border: 1px solid rgba(225,6,0,0.3);   color: #fca5a5; }
</style>

{{-- Hero --}}
<section class="admin-hero text-white">
    <div class="hero-stripe top"></div>
    <div class="hero-stripe bottom"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 py-12 text-center">
        <p class="audiowide-regular text-xs text-white/60 tracking-[6px] mb-2">ADMIN · CONTROL CENTER</p>
        <h2 class="audiowide-regular text-4xl md:text-5xl font-bold mb-3">
            User <span style="color:#e10600;text-shadow:0 0 20px rgba(225,6,0,0.8);">Management</span>
        </h2>
        <p class="text-gray-400 max-w-xl mx-auto text-sm">Search, edit roles, reset passwords, and remove users from the platform.</p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 pb-20 text-white">

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="flash success">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash error">✕ {{ session('error') }}</div>
    @endif

    {{-- Filter bar --}}
    <form method="GET" class="flex flex-col sm:flex-row gap-3 mb-8 items-stretch sm:items-center">
        <div class="relative flex-1">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z"/>
            </svg>
            <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or email…"
                   class="filter-input w-full pl-10">
        </div>
        <select name="role" class="filter-select">
            <option value="">All Roles</option>
            <option value="user"  {{ $role === 'user'  ? 'selected' : '' }}>User</option>
            <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admin</option>
        </select>
        <button type="submit" class="btn-f1">Filter</button>
        <a href="{{ route('admin.users.index') }}" class="action-btn role-btn" style="border-radius:9999px;padding:0.6rem 1rem;">Clear</a>
    </form>

    {{-- Table --}}
    <div class="section-title audiowide-regular text-xl font-bold uppercase text-white mb-6">
        Users
        <small class="text-white/30 text-sm ml-3 font-normal tracking-normal">{{ $users->total() }} total</small>
    </div>

    <div style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.07);border-radius:1rem;overflow:hidden;" class="mb-8">
        <div style="overflow-x:auto;">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Favorite Team</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <span class="audiowide-regular text-sm text-white font-semibold">{{ $user->name }}</span>
                            @if($user->id === Auth::id())
                                <span class="role-badge admin ml-2" style="font-size:0.5rem;">You</span>
                            @endif
                        </td>
                        <td class="text-white/50 text-sm">{{ $user->email }}</td>
                        <td>
                            <span class="role-badge {{ $user->role }}">{{ $user->role }}</span>
                        </td>
                        <td class="text-white/40 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="text-white/50 text-xs">
                            @if($user->favoriteConstructor)
                                <span style="color:{{ config('f1.team_colors.'.$user->favoriteConstructor->name, '#e10600') }}">
                                    {{ $user->favoriteConstructor->name }}
                                </span>
                            @else
                                <span class="text-white/20">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center gap-2 justify-end flex-wrap">
                                @if($user->id !== Auth::id())
                                    {{-- Role toggle --}}
                                    <button type="button"
                                            class="action-btn role-btn"
                                            onclick="openRoleModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->role }}')">
                                        Change Role
                                    </button>

                                    {{-- Reset password --}}
                                    <button type="button"
                                            class="action-btn pw-btn"
                                            onclick="openPwModal({{ $user->id }}, '{{ $user->name }}')">
                                        Reset PW
                                    </button>

                                    {{-- Delete --}}
                                    <form method="POST" action="{{ route('admin.users.delete', $user) }}"
                                          onsubmit="return confirm('Delete {{ addslashes($user->name) }}? This cannot be undone.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="action-btn del-btn">Delete</button>
                                    </form>
                                @else
                                    <span class="text-white/20 text-xs audiowide-regular">—</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-white/30 audiowide-regular py-10 text-sm">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
    @php
        $cur  = $users->currentPage();
        $last = $users->lastPage();
        $urls = $users->getUrlRange(1, $last);
        $winStart = max(1, min($cur, $last - 5));
        $winEnd   = min($last, $winStart + 5);
        $pages = [];
        if ($winStart > 1) { $pages[] = 1; if ($winStart > 2) $pages[] = null; }
        for ($p = $winStart; $p <= $winEnd; $p++) $pages[] = $p;
        if ($winEnd < $last) { if ($winEnd < $last - 1) $pages[] = null; $pages[] = $last; }
    @endphp
    <div class="f1-pagination pb-4">
        @if($users->onFirstPage())
            <span class="disabled">← Prev</span>
        @else
            <a href="{{ $users->previousPageUrl() }}">← Prev</a>
        @endif
        @foreach($pages as $page)
            @if(is_null($page))
                <span style="color:rgba(255,255,255,0.25);padding:0 0.25rem;">…</span>
            @elseif($page == $cur)
                <span class="active">{{ $page }}</span>
            @else
                <a href="{{ $urls[$page] }}">{{ $page }}</a>
            @endif
        @endforeach
        @if($users->hasMorePages())
            <a href="{{ $users->nextPageUrl() }}">Next →</a>
        @else
            <span class="disabled">Next →</span>
        @endif
    </div>
    @endif

</div>

{{-- Role Modal --}}
<div class="modal-backdrop" id="role-modal">
    <div class="modal-box">
        <p class="modal-title">Change Role</p>
        <p class="text-white/50 text-sm mb-4" id="role-modal-label"></p>
        <form method="POST" id="role-form">
            @csrf @method('PATCH')
            <select name="role" class="modal-input" style="border-radius:0.5rem;cursor:pointer;" id="role-select">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <div class="modal-actions">
                <button type="button" class="modal-cancel" onclick="closeModal('role-modal')">Cancel</button>
                <button type="submit" class="btn-f1" style="border-radius:0.4rem;">Save</button>
            </div>
        </form>
    </div>
</div>

{{-- Password Modal --}}
<div class="modal-backdrop" id="pw-modal">
    <div class="modal-box">
        <p class="modal-title">Reset Password</p>
        <p class="text-white/50 text-sm mb-4" id="pw-modal-label"></p>
        <form method="POST" id="pw-form">
            @csrf @method('PATCH')
            <input type="password" name="password" placeholder="New password (min 8 chars)" class="modal-input" required minlength="8">
            <input type="password" name="password_confirmation" placeholder="Confirm password" class="modal-input" required>
            <div class="modal-actions">
                <button type="button" class="modal-cancel" onclick="closeModal('pw-modal')">Cancel</button>
                <button type="submit" class="btn-f1" style="border-radius:0.4rem;">Reset</button>
            </div>
        </form>
    </div>
</div>

<script>
const baseRoleUrl = '{{ url('/admin/users') }}';

function openRoleModal(id, name, currentRole) {
    document.getElementById('role-modal-label').textContent = 'Changing role for: ' + name;
    document.getElementById('role-form').action = baseRoleUrl + '/' + id + '/role';
    document.getElementById('role-select').value = currentRole;
    document.getElementById('role-modal').classList.add('open');
}

function openPwModal(id, name) {
    document.getElementById('pw-modal-label').textContent = 'Resetting password for: ' + name;
    document.getElementById('pw-form').action = baseRoleUrl + '/' + id + '/password';
    document.getElementById('pw-modal').classList.add('open');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('open');
}

// Close on backdrop click
document.querySelectorAll('.modal-backdrop').forEach(el => {
    el.addEventListener('click', e => { if (e.target === el) el.classList.remove('open'); });
});

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') document.querySelectorAll('.modal-backdrop.open').forEach(el => el.classList.remove('open'));
});
</script>
</x-app-layout>
