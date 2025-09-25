<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Services\JolpicaF1Service;
use App\Models\Driver;
use App\Models\Constructor;
use App\Models\Standing;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    
    public function syncStandings($year = 2025)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        else{
        $response = Http::get("https://api.jolpi.ca/ergast/f1/{$year}/driverstandings/");
        if ($response->failed()) {
            abort(500, 'Unable to fetch standings data');
        }
    
        $data = $response->json();
    
        if (empty($data['MRData']['StandingsTable']['StandingsLists'][0]['DriverStandings'])) {
            return redirect()->back()->with('error', 'No standings data found for this season.');
        }
    
        $season = $data['MRData']['StandingsTable']['season'];
        $round  = $data['MRData']['StandingsTable']['round'];
        $standingsList = $data['MRData']['StandingsTable']['StandingsLists'][0]['DriverStandings'];
    
        // 1️⃣ Delete old standings for this season
        Standing::where('season', $season)->delete();
    
        // 2️⃣ Insert fresh standings
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
                'driver_id'      => $driver->id,
                'constructor_id' => $constructor->id,
                'season'         => $season,
                'round'          => $round,
                'position'       => $entry['position'],
                'points'         => $entry['points'],
                'wins'           => $entry['wins'],
            ]);
        }
    
        return redirect()->back()->with('success', "Standings for {$season} synced successfully.");
    }
    }

    public function index()
    {
        $drivers = Driver::with(['latestStanding.constructor'])->get();
        return view('drivers.index2', compact('drivers'));
    }


public function showStandings()
{   
    $season = date('Y'); // current season

    $standings = DB::table('standings')
        ->join('drivers', 'standings.driver_id', '=', 'drivers.id')
        ->join('constructors', 'standings.constructor_id', '=', 'constructors.id')
        ->select(
            'standings.position',
            'standings.points',
            'standings.wins',
            'drivers.id as id', // primary key for show route
            'drivers.driver_id', // Ergast-style ID if you use that
            'drivers.given_name',
            'drivers.family_name',
            'drivers.code',
            'drivers.nationality as driver_nationality',
            'constructors.name as constructor_name',
            'constructors.nationality as constructor_nationality'
        )
        ->where('standings.season', $season)
        ->orderBy('standings.position')
        ->get();
        
    return view('standings.index', compact('standings', 'season'));
}


private function fetchAllDriverResults(string $driverCode): array
{
    $allRaces = [];
    $limit = 100;       // API page size ceiling
    $offset = 0;

    while (true) {
        $url = "https://api.jolpi.ca/ergast/f1/drivers/{$driverCode}/results.json";
        $response = Http::timeout(15)->get($url, [
            'limit' => $limit,
            'offset' => $offset,
        ]);

        if (!$response->successful()) {
            break; // or throw if you prefer: abort(502, 'Failed to fetch results');
        }

        $json = $response->json()['MRData'] ?? [];
        $races = $json['RaceTable']['Races'] ?? [];
        $total = (int)($json['total'] ?? 0);

        if (empty($races)) {
            break;
        }

        $allRaces = array_merge($allRaces, $races);

        $offset += $limit;
        if ($offset >= $total) {
            break;
        }

        // Gentle pacing to be nice to the API (tweak as needed)
        usleep(150000); // 150ms
    }

    return $allRaces;
}

public function showDriver(Driver $driver)
{
    $driver->load(['latestStanding.constructor']);

    // Compute and cache career stats to reduce API calls (optional)
        $careerStats = Cache::remember("driver:{$driver->driver_id}:career", now()->addHours(6), function () use ($driver) {
        $races = $this->fetchAllDriverResults($driver->driver_id); // full pagination

        // Flatten to the primary classified result per race
        $results = collect($races)->map(fn($race) => $race['Results'][0]);

        // Helpers
        $isWin = fn($res) => isset($res['position']) && (int)$res['position'] === 1;
        $isPodium = fn($res) => isset($res['position']) && (int)$res['position'] <= 3;
        $isPole = fn($res) => isset($res['grid']) && (int)$res['grid'] === 1;

        // Status classification: count as DNF if not Finished and not classified (+n Laps)
        $isDNF = function ($res) {
            $status = strtolower($res['status'] ?? '');
            if ($status === 'finished') return false;
            // Classified but lapped finishes look like "+1 Lap", "+2 Laps"
            if (preg_match('/^\+?\d+\s+laps?$/i', $status)) return false;
            return true; // Accident, Engine, Disqualified, Collision, etc.
        };

        $points = $results->sum(fn($r) => (float)($r['points'] ?? 0));
        $wins = $results->filter($isWin)->count();
        $podiums = $results->filter($isPodium)->count();
        $poles = $results->filter($isPole)->count();
        $dnfs = $results->filter($isDNF)->count();

        // Optional: fastest laps if available in payload
        $fastestLaps = $results->filter(function ($r) {
            return isset($r['FastestLap']['rank']) && (string)$r['FastestLap']['rank'] === '1';
        })->count();

        return [
            'races' => $results->count(),
            'points' => $points,
            'wins' => $wins,
            'podiums' => $podiums,
            'poles' => $poles,
            'dnfs' => $dnfs,
            'fastest_laps' => $fastestLaps,
        ];
    });

    return view('drivers.show', compact('driver', 'careerStats'));
}
}
?>