<x-app-layout>
<style>
    .standings-container {
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
    .constructor-name {
        font-weight: bold;
    }

    /* Mobile styles */
    @media (max-width: 768px) {
        table, thead, tbody, th, td, tr {
            display: block;
            width: 100%;
        }
        thead {
            display: none; /* hide table header */
        }
        tr {
            margin-bottom: 1rem;
            background-color: #2a2a2a;
            border-radius: 8px;
            padding: 0.5rem;
        }
        td {
            padding: 0.5rem;
            text-align: right;
            position: relative;
        }
        td::before {
            content: attr(data-label);
            position: absolute;
            left: 0.75rem;
            font-weight: bold;
            text-align: left;
            color: rgb(156 163 175);
        }
    }
</style>

<div class="px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto mt-8">
    <div class="standings-container">
        <h2 class="text-2xl font-bold mb-4 text-center audiowide-regular">
            {{ $season }} Constructors Standings
        </h2>

        <table>
            <thead>
                <tr>
                    <th>Pos</th>
                    <th>Constructor</th>
                    <th>Nationality</th>
                    <th>Points</th>
                    <th>Wins</th>
                </tr>
            </thead>
            <tbody>
                @foreach($standings as $index => $row)
                    <tr>
                        <td data-label="Pos">{{ $index + 1 }}</td>
                        <td data-label="Constructor" class="constructor-name">{{ $row->constructor_name }}</td>
                        <td data-label="Nationality">{{ $row->constructor_nationality }}</td>
                        <td data-label="Points">{{ $row->points }}</td>
                        <td data-label="Wins">{{ $row->wins }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</x-app-layout>
