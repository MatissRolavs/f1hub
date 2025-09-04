<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use App\Services\JolpicaF1Service;
use App\Models\Driver;

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
  

public function syncDrivers()
{
    $url = "https://api.jolpi.ca/ergast/f1/current/drivers.json?limit=100";
    $response = Http::get($url);

    if ($response->failed()) {
        abort(500, 'API request failed');
    }

    $drivers = $response->json()['MRData']['DriverTable']['Drivers'] ?? [];
    foreach ($drivers as $d) {
        Driver::updateOrCreate(
            ['driver_id' => $d['driverId']],
            [
                'given_name'       => $d['givenName'],
                'family_name'      => $d['familyName'],
                'nationality'      => $d['nationality'] ?? null,
                'permanent_number' => $d['permanentNumber'] ?? null,
                'url'              => $d['url'] ?? null,
            ]
        );
    }
    
    return view('drivers.sync');
    
}
public function index()
{
    $drivers = Driver::orderBy('family_name')->get();
    return view('drivers.index', compact('drivers'));
}
}

?>