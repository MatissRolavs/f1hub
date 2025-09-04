<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use App\Services\JolpicaF1Service;
use App\Models\Driver2;
use App\Models\Driver;
use App\Models\Constructor;
use App\Models\Standing;

class F1Controller extends Controller
{
    protected $f1;

    public function __construct(JolpicaF1Service $f1)
    {
        $this->f1 = $f1;
    }

    public function driverStandings($year = 'current')
    {
        $url = "http://api.jolpi.ca/ergast/f1/{$year}/driverstandings.json";

        $response = Http::get($url);

        if ($response->failed()) {
            abort(500, 'Unable to fetch standings');
        }

        $data = $response->json();

        $standingsList = $data['MRData']['StandingsTable']['StandingsLists'][0] ?? null;

        return view('drivers.standings', [
            'season'    => $standingsList['season'] ?? $year,
            'round'     => $standingsList['round'] ?? '',
            'standings' => $standingsList['DriverStandings'] ?? []
        ]);
    }

    public function allDrivers()
    {
        $url = "https://api.jolpi.ca/ergast/f1/current/drivers.json?limit=1000"; 
        // limit=1000 to get all drivers

        $response = Http::get($url);

        if ($response->failed()) {
            abort(500, 'Unable to fetch drivers');
        }

        $data = $response->json();

        $drivers = $data['MRData']['DriverTable']['Drivers'] ?? [];

        return view('drivers1.index', [
            'drivers' => $drivers
        ]);
    }
  

// public function syncDrivers()
// {
//     $url = "https://api.jolpi.ca/ergast/f1/current/drivers.json?limit=100";
//     $response = Http::get($url);

//     if ($response->failed()) {
//         abort(500, 'API request failed');
//     }

//     $drivers = $response->json()['MRData']['DriverTable']['Drivers'] ?? [];
//     foreach ($drivers as $d) {
//         Driver::updateOrCreate(
//             ['driver_id' => $d['driverId']],
//             [
//                 'given_name'       => $d['givenName'],
//                 'family_name'      => $d['familyName'],
//                 'nationality'      => $d['nationality'] ?? null,
//                 'permanent_number' => $d['permanentNumber'] ?? null,
//                 'url'              => $d['url'] ?? null,
//             ]
//         );
//     }
    
//     return view('drivers.sync');
    
// }
// public function index()
// {
//     $drivers = Driver::orderBy('family_name')->get();
//     return view('drivers.index', compact('drivers'));
// }
public function syncStandings($year = 2025)
{
    $response = Http::get("https://api.jolpi.ca/ergast/f1/{$year}/driverstandings/");
    $data = $response->json();

    $standingsList = $data['MRData']['StandingsTable']['StandingsLists'][0]['DriverStandings'];

    foreach ($standingsList as $entry) {
        $driverData = $entry['Driver'];
        $constructorData = $entry['Constructors'][0];

        // Save or update driver
        $driver = Driver::updateOrCreate(
            ['driver_id' => $driverData['driverId']],
            [
                'code' => $driverData['code'] ?? null,
                'permanent_number' => $driverData['permanentNumber'] ?? null,
                'given_name' => $driverData['givenName'],
                'family_name' => $driverData['familyName'],
                'date_of_birth' => $driverData['dateOfBirth'],
                'nationality' => $driverData['nationality'],
                'url' => $driverData['url'] ?? null,
            ]
        );

        // Save or update constructor
        $constructor = Constructor::updateOrCreate(
            ['constructor_id' => $constructorData['constructorId']],
            [
                'name' => $constructorData['name'],
                'nationality' => $constructorData['nationality'],
                'url' => $constructorData['url'] ?? null,
            ]
        );

        // Save standings
        Standing::create([
            'driver_id' => $driver->id,
            'constructor_id' => $constructor->id,
            'season' => $data['MRData']['StandingsTable']['season'],
            'round' => $data['MRData']['StandingsTable']['round'],
            'position' => $entry['position'],
            'points' => $entry['points'],
            'wins' => $entry['wins'],
        ]);
    }
}
public function sync()
{
    $this->openF1->syncStandings(2025);
    return redirect()->route('drivers1.index')->with('success', 'Standings synced!');
}
public function index()
{
    $drivers = Driver::with(['latestStanding.constructor'])->get();
    return view('drivers.index2', compact('drivers'));
}
}

?>