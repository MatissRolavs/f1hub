


    <div class="container">
        <h1 class="mb-4">F1 Drivers - 2025</h1>

        @if($drivers && count($drivers))
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Team</th>
                        <th>Number</th>
                        <th>Picture</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($drivers as $driver)
                        <tr>
                            <td style="color: {{$driver['team_colour']}}">{{ $driver['full_name'] ?? 'N/A' }}</td>
                            <td style="color: {{$driver['team_colour']}}">{{ $driver['team_name'] ?? 'N/A' }}</td>
                            <td style="color: {{$driver['team_colour']}}">{{ $driver['driver_number'] ?? 'N/A' }}</td>
                            <td><img src="{{ $driver['headshot_url'] ?? 'N/A' }}" alt="{{ $driver['full_name'] ?? 'N/A' }}" width="50" height="50"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No drivers found for 2024.</p>
        @endif
        
    </div>

