<x-app-layout>
<style>
    .race-results-container {
        background-color: #1a1a1a;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.4);
        color: #fff;
        font-family: monospace;
        padding: 2rem;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 0.75rem;
        text-align: left;
    }
    th {
        background-color: #222;
        color: rgb(156 163 175);
        text-transform: uppercase;
        font-size: 0.9rem;
    }
    tr:nth-child(even) {
        background-color: #2a2a2a;
    }
    tr:hover {
        background-color: #333;
    }
    .driver-name {
        font-weight: bold;
    }
    .driver-code {
        color: #ccc;
        font-size: 0.85rem;
    }
</style>

{{-- Matches navbar padding, limits width, and adds top margin --}}
<div class="px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto mt-8">
    <div class="race-results-container">
        <h2 class="text-2xl font-bold mb-2 text-center audiowide-regular">
            🏁 {{ $raceName }} — {{ $season }}
        </h2>
        <p class="text-center text-gray-400 mb-6 audiowide-regular">
            Round {{ $round }} — {{ \Carbon\Carbon::parse($raceDate)->format('D, d M Y') }}
        </p>

        <table>
            <thead>
                <tr>
                    <th>Pos</th>
                    <th>Driver</th>
                    <th>Nationality</th>
                    <th>Constructor</th>
                    <th>Grid</th>
                    <th>Laps</th>
                    <th>Time</th>
                    <th>Points</th>
                    <th>Fastest Lap</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $row)
                    <tr>
                        <td>{{ $row->position_text }}</td>
                        <td>
                            <span class="driver-name">{{ $row->given_name }} {{ $row->family_name }}</span>
                            @if($row->code)
                                <span class="driver-code">({{ $row->code }})</span>
                            @endif
                        </td>
                        <td>{{ $row->driver_nationality }}</td>
                        <td>{{ $row->constructor_name }}</td>
                        <td>{{ $row->grid }}</td>
                        <td>{{ $row->laps }}</td>
                        <td>{{ $row->race_time ?? '—' }}</td>
                        <td>{{ $row->points }}</td>
                        <td>
                            @if($row->fastest_lap_time)
                                {{ $row->fastest_lap_time }} (Rank {{ $row->fastest_lap_rank }})
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</x-app-layout>
