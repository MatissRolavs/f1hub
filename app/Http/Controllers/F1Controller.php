<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Services\JolpicaF1Service;
use App\Models\Driver2;
use App\Models\Driver;
use App\Models\Constructor;
use App\Models\Race;
use App\Models\Standing;
use App\Models\Race_result;
use App\Models\GameScore;
use App\Models\RacePrediction;
use App\Models;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

    public function syncStandings($year = 2025)
    {
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
public function sync()
{
    $this->openF1->syncStandings(2025);
    return redirect()->route('drivers.index2')->with('success', 'Standings synced!');
}
public function index()
{
    $drivers = Driver::with(['latestStanding.constructor'])->get();
    return view('drivers.index2', compact('drivers'));
}
public function currentSeasonRaces()
{
    // 1️⃣ Fetch races from Ergast API
    $response = Http::get("https://api.jolpi.ca/ergast/f1/current.json");
    if ($response->failed()) {
        abort(500, 'Unable to fetch race data');
    }

    $data = $response->json();
    $races = $data['MRData']['RaceTable']['Races'] ?? [];

    // 2️⃣ Sort by date ascending
    usort($races, fn($a, $b) => strtotime($a['date']) <=> strtotime($b['date']));

    // 3️⃣ Store/update races in DB
    foreach ($races as $race) {
        // Convert ISO 8601 time (with Z) to MySQL TIME format
        $time = null;
        if (!empty($race['time'])) {
            $time = Carbon::parse($race['date'] . 'T' . $race['time'])->toTimeString();
        }

        Race::updateOrCreate(
            [
                'season' => $data['MRData']['RaceTable']['season'],
                'round'  => $race['round']
            ],
            [
                'name'         => $race['raceName'],
                'date'         => $race['date'],
                'time'         => $time,
                'circuit_name' => $race['Circuit']['circuitName'],
                'locality'     => $race['Circuit']['Location']['locality'],
                'country'      => $race['Circuit']['Location']['country'],
                'url'          => $race['url'] ?? null
            ]
        );
    }

    // 4️⃣ Find the first upcoming race index
    $today = Carbon::today();
    $startIndex = 0;
    foreach ($races as $i => $race) {
        $raceDate = Carbon::parse($race['date']);
        if ($raceDate->isToday() || $raceDate->isFuture()) {
            $startIndex = $i;
            break;
        }
        if ($i === count($races) - 1) {
            $startIndex = max(count($races) - 1, 0);
        }
    }

    // 5️⃣ Pass data to view
    return redirect()->back()->with('success', 'Current season races have been updated.');
}
public function showRacesFromDb()
{
    $races = Race::orderBy('date')->get();

    // Find the first upcoming race index
    $today = Carbon::today();
    $startIndex = 0;
    foreach ($races as $i => $race) {
        $raceDate = Carbon::parse($race->date);
        if ($raceDate->isToday() || $raceDate->isFuture()) {
            $startIndex = $i;
            break;
        }
        if ($i === count($races) - 1) {
            $startIndex = max(count($races) - 1, 0);
        }
    }

    return view('races.index', [
        'races'      => $races,
        'startIndex' => $startIndex,
    ]);
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
public function syncSeasonRaceResults($year = 2025)
{
    $limit = 100;
    $offset = 0;
    $allRaces = [];

    \Log::info("=== Starting race results sync for {$year} ===");

    do {
        $url = "https://api.jolpi.ca/ergast/f1/{$year}/results.json";
        $response = Http::get($url, [
            'limit' => $limit,
            'offset' => $offset
        ]);

        if ($response->failed()) {
            \Log::error("API request failed at offset {$offset}");
            return redirect()->back()->with('error', 'Unable to fetch race results.');
        }

        $data = $response->json();
        $total = (int) ($data['MRData']['total'] ?? 0);
        $races = $data['MRData']['RaceTable']['Races'] ?? [];

        if (empty($races)) break;

        $allRaces = array_merge($allRaces, $races);
        $offset += $limit;

    } while (count($allRaces) < $total);

    if (empty($allRaces)) {
        return redirect()->back()->with('error', 'No race results found for this season.');
    }

    // Delete old results for this season
    Race_result::where('season', $year)->delete();

    $insertCount = 0;

    foreach ($allRaces as $race) {
        foreach ($race['Results'] as $result) {
            try {
                $driverData = $result['Driver'];
                $constructorData = $result['Constructor'];

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

                $constructor = Constructor::updateOrCreate(
                    ['constructor_id' => $constructorData['constructorId']],
                    [
                        'name' => $constructorData['name'],
                        'nationality' => $constructorData['nationality'],
                        'url' => $constructorData['url'] ?? null,
                    ]
                );

                Race_result::create([
                    'season' => $race['season'],
                    'round' => $race['round'],
                    'race_name' => $race['raceName'],
                    'date' => $race['date'],
                    'time' => !empty($race['time']) ? Carbon::parse($race['date'] . 'T' . $race['time'])->toTimeString() : null,
                    'driver_id' => $driver->id,
                    'constructor_id' => $constructor->id,
                    'grid' => $result['grid'],
                    'position' => is_numeric($result['position']) ? (int)$result['position'] : null,
                    'position_text' => $result['positionText'],
                    'points' => $result['points'],
                    'laps' => $result['laps'],
                    'status' => $result['status'],
                    'race_time' => $result['Time']['time'] ?? null,
                    'fastest_lap_time' => $result['FastestLap']['Time']['time'] ?? null,
                    'fastest_lap_rank' => $result['FastestLap']['rank'] ?? null,
                    'fastest_lap_speed' => $result['FastestLap']['AverageSpeed']['speed'] ?? null,
                ]);

                $insertCount++;
            } catch (\Exception $e) {
                \Log::error("Insert failed for {$race['raceName']} - {$driverData['givenName']} {$driverData['familyName']}: " . $e->getMessage());
            }
        }

        // ✅ Only score if race date is in the past
        if (!empty($race['date']) && \Carbon\Carbon::parse($race['date'])->isPast()) {
            $this->scorePredictionsForRace($race['season'], $race['round'], $race['raceName']);
        } else {
            \Log::info("Skipping scoring for {$race['raceName']} — race date is in the future.");
        }
    }

    return redirect()->back()->with('success', "Race results for {$year} synced successfully. Inserted {$insertCount} entries and scored past races.");
}
protected function scorePredictionsForRace($season, $round, $raceName)
{
    $actualOrder = Race_result::where('season', $season)
        ->where('round', $round)
        ->orderBy('position')
        ->with('driver') // relationship to Driver model
        ->get()
        ->pluck('driver.driver_id') // Ergast ID
        ->values()
        ->toArray();

    if (empty($actualOrder)) {
        \Log::info("No actual order found for {$raceName}, skipping scoring.");
        return;
    }

    $predictions = RacePrediction::where('season', $season)
        ->where('round', $round)
        ->get();

    foreach ($predictions as $prediction) {
        $predictedOrder = json_decode($prediction->predicted_order, true);
        if (empty($predictedOrder)) continue;

        $score = 0;
        foreach ($predictedOrder as $i => $driverId) {
            if (($actualOrder[$i] ?? null) == $driverId) {
                $score++;
            }
        }

        GameScore::updateOrCreate(
            [
                'race_id' => null,
                'race_name' => $raceName,
                'player_name' => Auth::user()->name ?? 'Unknown Player',
            ],
            [
                'score' => $score,
                'total' => count($actualOrder),
            ]
        );
    }

    \Log::info("Scored predictions for {$raceName}");
}



public function raceShow($season, $round)
{
    $results = DB::table('race_results')
        ->join('drivers', 'race_results.driver_id', '=', 'drivers.id')
        ->join('constructors', 'race_results.constructor_id', '=', 'constructors.id')
        ->select(
            'race_results.*',
            'drivers.given_name',
            'drivers.family_name',
            'drivers.code',
            'drivers.nationality as driver_nationality',
            'constructors.name as constructor_name',
            'constructors.nationality as constructor_nationality'
        )
        ->where('race_results.season', $season)
        ->where('race_results.round', $round)
        ->orderBy('race_results.position')
        ->get();

    if ($results->isEmpty()) {
        return redirect()->back()->with('error', 'No results found for this race.');
    }

    $raceName = $results->first()->race_name;
    $raceDate = $results->first()->date;

    return view('races.show', compact('results', 'season', 'round', 'raceName', 'raceDate'));
}
public function constructorsStandings($season)
{
    $standings = DB::table('standings')
        ->join('constructors', 'standings.constructor_id', '=', 'constructors.id')
        ->select(
            'constructors.name as constructor_name',
            'constructors.nationality as constructor_nationality',
            DB::raw('SUM(standings.points) as points'),
            DB::raw('SUM(standings.wins) as wins')
        )
        ->where('standings.season', $season)
        ->groupBy('constructors.name', 'constructors.nationality')
        ->orderByDesc('points')
        ->get();

    return view('standings.constructors', compact('standings', 'season'));
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