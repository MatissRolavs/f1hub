
<div class="container">
    <h2>F1 Driver Standings — {{ $season }} @if($round) (Round {{ $round }}) @endif</h2>

    @if(count($standings))
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Pos</th>
                    <th>Driver</th>
                    <th>Nationality</th>
                    <th>Constructor(s)</th>
                    <th>Wins</th>
                    <th>Points</th>
                </tr>
            </thead>
            <tbody>
                @foreach($standings as $driver)
                    <tr>
                        <td>{{ $driver['positionText'] }}</td>
                        <td>
                            <a href="{{ $driver['Driver']['url'] }}" target="_blank">
                                {{ $driver['Driver']['givenName'] }} {{ $driver['Driver']['familyName'] }}
                            </a>
                        </td>
                        <td>{{ $driver['Driver']['nationality'] }}</td>
                        <td>
                            @foreach($driver['Constructors'] as $index => $constructor)
                                <a href="{{ $constructor['url'] }}" target="_blank">{{ $constructor['name'] }}</a>@if($index < count($driver['Constructors']) - 1), @endif
                            @endforeach
                        </td>
                        <td>{{ $driver['wins'] }}</td>
                        <td>{{ $driver['points'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No standings available.</p>
    @endif
</div>

