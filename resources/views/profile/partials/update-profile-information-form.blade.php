<link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
<style>
    .font-audiowide { font-family: 'Audiowide', cursive; }

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
            padding-left: 1rem;
            display: inline-block;
        }
        .section-title::before {
            content: "";
            position: absolute;
            left: 0; top: 10%; bottom: 10%;
            width: 5px;
            background: #e10600;
            box-shadow: 0 0 10px rgba(225,6,0,0.6);
        }

        /* ── Hero ─────────────────────────────────────────────── */
        .profile-hero {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(ellipse at 50% 20%, rgba(225,6,0,0.25) 0%, transparent 55%),
                linear-gradient(135deg, #0a0a0f 0%, #15151e 60%, #0a0a0f 100%);
        }
        .profile-hero::before {
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

        .profile-hero h2 {
            letter-spacing: 3px;
            text-shadow: 0 0 30px rgba(225,6,0,0.35);
        }
        .profile-hero h2 .accent {
            color: #e10600;
            text-shadow: 0 0 20px rgba(225,6,0,0.8);
        }

        /* ── Avatar ──────────────────────────────────────────── */
        .avatar {
            width: 6rem; height: 6rem;
            border-radius: 9999px;
            display: flex; align-items: center; justify-content: center;
            font-family: "Audiowide", sans-serif;
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, #e10600 0%, #7a0300 100%);
            color: white;
            border: 3px solid rgba(255,255,255,0.15);
            box-shadow: 0 0 25px rgba(225,6,0,0.4);
            flex-shrink: 0;
        }

        /* ── Card ────────────────────────────────────────────── */
        .profile-card {
            position: relative;
            background: linear-gradient(135deg, #1a1a28 0%, #0f0f15 100%);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 1rem;
            padding: 2rem;
            overflow: hidden;
        }
        .profile-card::before {
            content: "";
            position: absolute; top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, #e10600 0%, #e10600 55%, transparent 100%);
            box-shadow: 0 0 12px rgba(225,6,0,0.5);
        }
        .profile-card.danger::before {
            background: #e10600;
            box-shadow: 0 0 15px rgba(225,6,0,0.8);
        }
        .profile-card.danger {
            border-color: rgba(225,6,0,0.3);
        }

        /* ── Input / select ──────────────────────────────────── */
        .f1-input, .f1-select {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 0.75rem;
            color: white;
            transition: border-color 0.25s ease, box-shadow 0.25s ease;
        }
        .f1-input::placeholder { color: rgba(255,255,255,0.4); }
        .f1-input:focus, .f1-select:focus {
            outline: none;
            border-color: rgba(225,6,0,0.9);
            box-shadow: 0 0 15px rgba(225,6,0,0.35);
        }
        .f1-select option { background: #15151e; color: white; }
        .f1-select:disabled { opacity: 0.5; cursor: not-allowed; }
        .f1-label {
            display: block;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: rgba(255,255,255,0.55);
            margin-bottom: 0.5rem;
        }

        /* ── Buttons ─────────────────────────────────────────── */
        .btn-f1 {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.85rem 1.75rem;
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
            padding: 0.85rem 1.75rem;
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

    @media (prefers-reduced-motion: reduce) {
        .reveal, .reveal-scale { opacity: 1 !important; transform: none !important; transition: none !important; }
    }
</style>

@php
    $favConstructor = $user->favoriteConstructor;
    $favDriver      = $user->favoriteDriver;
    $favColor       = $favConstructor
        ? config('f1.team_colors.' . $favConstructor->name, config('f1.default_team_color'))
        : null;
    $initial        = strtoupper(mb_substr($user->name, 0, 1));
@endphp

{{-- ───────────────────────── HERO ───────────────────────── --}}
<section class="profile-hero text-white">
    <div class="hero-stripe top"></div>
    <div class="hero-stripe bottom"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 py-12">
        <div class="reveal-scale flex flex-col sm:flex-row items-center gap-6 text-center sm:text-left">
            <div class="avatar" @if($favColor) style="background: linear-gradient(135deg, {{ $favColor }} 0%, rgba(0,0,0,0.6) 100%); box-shadow: 0 0 25px {{ $favColor }}66;" @endif>
                {{ $initial }}
            </div>

            <div class="flex-1 min-w-0">
                <p class="audiowide-regular text-xs text-white/60 tracking-[4px] mb-1">PROFILE SETTINGS</p>
                <h2 class="audiowide-regular text-3xl md:text-5xl font-bold break-words">
                    {{ $user->name }}
                </h2>
                <p class="text-sm text-white/60 mt-1 break-all">{{ $user->email }}</p>

                @if($favDriver && $favConstructor)
                    <div class="mt-3 flex items-center gap-2 justify-center sm:justify-start">
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold text-white"
                              style="background-color: {{ $favColor }}; text-shadow: 0 1px 1px rgba(0,0,0,0.6);">
                            #{{ $favDriver->permanent_number ?? '—' }}
                        </span>
                        <span class="text-sm text-white/70">
                            {{ $favDriver->given_name }} {{ $favDriver->family_name }}
                            <span class="text-white/40">•</span>
                            {{ $favConstructor->name }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-white audiowide-regular space-y-8">

    {{-- ───────────────────────── PROFILE INFO ───────────────────────── --}}
    <div class="profile-card reveal">
        <h3 class="section-title text-xl md:text-2xl font-bold uppercase mb-6">Account</h3>

        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf
            @method('patch')

            <input type="hidden" name="email" value="{{ old('email', $user->email) }}">

            <div>
                <label for="name" class="f1-label">Name</label>
                <input id="name" name="name" type="text" class="f1-input"
                       value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                <x-input-error class="mt-2 text-red-400" :messages="$errors->get('name')" />
            </div>

            {{-- Favorites --}}
            <div class="pt-4 border-t border-white/10"
                 x-data="{
                    team: @js((string) old('favorite_constructor_id', $user->favorite_constructor_id)),
                    driver: @js((string) old('favorite_driver_id', $user->favorite_driver_id)),
                    drivers: @js($driversByConstructor)
                 }"
                 x-init="$nextTick(() => { if (driver) $refs.driverSelect.value = driver })">
                <h4 class="section-title text-sm md:text-base font-bold uppercase mb-5">Favorites</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="favorite_constructor_id" class="f1-label">Favorite Team</label>
                        <select id="favorite_constructor_id" name="favorite_constructor_id"
                                x-model="team"
                                @change="driver = ''"
                                class="f1-select">
                            <option value="">— None —</option>
                            @foreach($favoriteConstructors as $constructor)
                                <option value="{{ $constructor->id }}">{{ $constructor->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2 text-red-400" :messages="$errors->get('favorite_constructor_id')" />
                    </div>

                    <div>
                        <label for="favorite_driver_id" class="f1-label">Favorite Driver</label>
                        <select id="favorite_driver_id" name="favorite_driver_id"
                                x-ref="driverSelect"
                                x-model="driver"
                                :disabled="!team"
                                class="f1-select">
                            <option value="">— None —</option>
                            <template x-for="d in (drivers[team] || [])" :key="d.id">
                                <option :value="d.id" x-text="d.name + (d.number ? ' #' + d.number : '')"></option>
                            </template>
                        </select>
                        <x-input-error class="mt-2 text-red-400" :messages="$errors->get('favorite_driver_id')" />
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="btn-f1">Save Changes</button>
            </div>
        </form>
    </div>

    {{-- ───────────────────────── CHANGE PASSWORD ───────────────────────── --}}
    <div class="profile-card reveal">
        <h3 class="section-title text-xl md:text-2xl font-bold uppercase mb-6">Change Password</h3>

        <form method="post" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            @method('put')

            <div>
                <label for="update_password_current_password" class="f1-label">Current Password</label>
                <input id="update_password_current_password" name="current_password" type="password"
                       class="f1-input" autocomplete="current-password">
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-red-400" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="update_password_password" class="f1-label">New Password</label>
                    <input id="update_password_password" name="password" type="password"
                           class="f1-input" autocomplete="new-password">
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-red-400" />
                </div>

                <div>
                    <label for="update_password_password_confirmation" class="f1-label">Confirm Password</label>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                           class="f1-input" autocomplete="new-password">
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-red-400" />
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="btn-f1">Update Password</button>
            </div>
        </form>
    </div>

    {{-- ───────────────────────── DANGER ZONE ───────────────────────── --}}
    <div class="profile-card danger reveal">
        <h3 class="section-title text-xl md:text-2xl font-bold uppercase mb-3 text-red-400">Danger Zone</h3>
        <p class="text-sm text-white/60 mb-6">
            Once your account is deleted, all of its resources and data will be permanently removed. There is no undo.
        </p>

        <x-danger-button
            class="px-6 py-3 rounded-lg"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
            Delete Account
        </x-danger-button>

        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                @csrf
                @method('delete')

                <h2 class="audiowide-regular text-xl font-bold text-white mb-2">
                    Delete your account?
                </h2>
                <p class="text-sm text-white/60 mb-5">
                    Enter your password to confirm. This action cannot be undone.
                </p>

                <input id="password" name="password" type="password" class="f1-input" placeholder="Password">
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-red-400" />

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" class="btn-ghost" x-on:click="$dispatch('close')">Cancel</button>
                    <x-danger-button class="px-5 py-3 rounded-lg">Delete Account</x-danger-button>
                </div>
            </form>
        </x-modal>
    </div>
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
