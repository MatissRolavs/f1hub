<x-app-layout>
<div class="container">
    <h2 class="mb-4">All Current Season Active F1 Drivers</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($drivers->count())
        <div class="row">
            @foreach($drivers as $driver)
                @php
                    

                    // Handle multi-part given names
                    $givenParts = explode(' ', $driver->given_name);
                    $givenForUrl = count($givenParts) > 1 ? $givenParts[1] : $givenParts[0];

                    // Normalize: remove accents, lowercase
                    $givenForUrl = Str::lower(Str::ascii($givenForUrl));
                    $familyForUrl = Str::lower(Str::ascii($driver->family_name));
                @endphp

                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img 
                            src="https://www.kymillman.com/wp-content/uploads/f1/pages/driver-profiles/driver-faces/{{ $givenForUrl }}-{{ $familyForUrl }}-f1-driver-profile-picture.png" 
                            alt="{{ $driver->given_name }} {{ $driver->family_name }}"
                            class="card-img-top"
                            style=" width: 250px;
                                    max-width: 250px;
                                    height: 205px;
                                    max-height: 250px;
                                    object-fit: contain;
                                    margin: auto;"
                            >

                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="{{ $driver->url }}" target="_blank" class="text-decoration-none">
                                    {{ $driver->given_name }} {{ $driver->family_name }}
                                </a>
                            </h5>
                            <p class="card-text mb-0"><strong>Number #</strong>{{ $driver->permanent_number ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p>No drivers found. <a href="{{ route('drivers.sync') }}">Sync now</a></p>
    @endif
</div>
</x-app-layout>