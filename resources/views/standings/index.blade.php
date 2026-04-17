<x-app-layout>
@include('standings.partials.styles')

{{-- ───────────────────────── HERO ───────────────────────── --}}
<section class="standings-hero min-h-[360px] text-white">
    <div class="hero-stripe top"></div>
    <div class="hero-stripe bottom"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 py-14">
        @if(session('success'))
            <div class="bg-green-600/20 border border-green-500/40 text-green-200 p-4 rounded-lg mb-8 text-center font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <div class="reveal-scale text-center">
            <p class="audiowide-regular text-xs md:text-sm text-white/60 tracking-[6px] mb-2">
                {{ $season }} SEASON
            </p>
            <h2 class="audiowide-regular text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
                Driver <span class="accent">Standings</span>
            </h2>

            {{-- Tabs + season selector --}}
            <div class="flex flex-col md:flex-row items-center justify-center gap-4 mt-6">
                <nav class="standings-tabs audiowide-regular">
                    <a href="{{ route('standings.index', ['season' => $season]) }}" class="active">Drivers</a>
                    <a href="{{ route('standings.constructors', ['season' => $season]) }}">Constructors</a>
                </nav>

                <form method="GET" action="{{ route('standings.index') }}" class="flex items-center gap-3">
                    <label for="season" class="text-xs md:text-sm text-white/70 audiowide-regular uppercase tracking-widest">Season</label>
                    <select name="season" id="season" onchange="this.form.submit()" class="season-select audiowide-regular">
                        @foreach($seasons as $season_option)
                            <option value="{{ $season_option }}" {{ $season_option == $selectedSeason ? 'selected' : '' }}>
                                {{ $season_option }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>
</section>

{{-- ───────────────────────── PODIUM (Top 3) ───────────────────────── --}}
@php
    $podium = $standings->take(3);
@endphp
@if($podium->count())
<section class="max-w-7xl mx-auto px-4 -mt-12 relative z-20">
    <div class="reveal-stagger grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($podium as $row)
            @php
                $teamColor = config('f1.team_colors.' . $row->constructor_name, config('f1.default_team_color'));
            @endphp
            <a href="{{ route('drivers.show', $row->driver_id) }}"
               class="podium-card block"
               style="background-color: {{ $teamColor }};">
                <div class="pos-medal medal-{{ $row->position }}">P{{ $row->position }}</div>
                <div class="relative z-10">
                    <p class="text-xs uppercase tracking-[3px] opacity-80">{{ $row->constructor_name }}</p>
                    <h3 class="audiowide-regular text-2xl md:text-3xl font-bold leading-tight mt-1 mb-4">
                        {{ $row->given_name }} {{ $row->family_name }}
                    </h3>
                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-widest opacity-80">Points</p>
                            <p class="audiowide-regular text-4xl font-extrabold leading-none">{{ $row->points }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs uppercase tracking-widest opacity-80">Wins</p>
                            <p class="audiowide-regular text-4xl font-extrabold leading-none">{{ $row->wins }}</p>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endif

{{-- ───────────────────────── FULL TABLE ───────────────────────── --}}
<section class="py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <h3 class="section-title audiowide-regular text-2xl md:text-3xl font-bold uppercase text-white mb-8 reveal">
            Full Classification
        </h3>

        @if($standings->count())
            <div class="overflow-x-auto reveal">
                <table class="standings-table audiowide-regular">
                    <thead>
                        <tr>
                            <th class="w-16">Pos</th>
                            <th>Driver</th>
                            <th class="hidden md:table-cell">Nationality</th>
                            <th>Team</th>
                            <th class="text-right">Points</th>
                            <th class="text-right hidden sm:table-cell">Wins</th>
                        </tr>
                    </thead>
                    <tbody class="reveal-stagger">
                        @foreach($standings as $row)
                            @php
                                $teamColor = config('f1.team_colors.' . $row->constructor_name, config('f1.default_team_color'));
                                $pos = (int) $row->position;
                            @endphp
                            <tr class="standings-row">
                                <td>
                                    <span class="pos-badge pos-{{ $pos }}">{{ $pos }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('drivers.show', $row->driver_id) }}" class="hover:text-red-400 transition">
                                        <span class="font-bold">{{ $row->given_name }} {{ $row->family_name }}</span>
                                        @if($row->code)
                                            <span class="text-white/40 text-sm ml-1">{{ $row->code }}</span>
                                        @endif
                                    </a>
                                </td>
                                <td class="hidden md:table-cell text-white/70">{{ $row->driver_nationality }}</td>
                                <td>
                                    <span class="team-color-stripe" style="background-color: {{ $teamColor }};"></span>
                                    <span class="text-white/90">{{ $row->constructor_name }}</span>
                                </td>
                                <td class="text-right points-cell">{{ $row->points }}</td>
                                <td class="text-right hidden sm:table-cell points-cell">{{ $row->wins }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-gray-400 py-16">No standings found for this season.</p>
        @endif
    </div>
</section>

@include('standings.partials.reveal-script')
</x-app-layout>
