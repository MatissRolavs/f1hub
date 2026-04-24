<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Driver;
use App\Models\Constructor;
use App\Models\Standing;
use Illuminate\Support\Facades\Auth;
use App\Services\JolpicaF1Service;

class DriverController extends Controller
{
    protected $f1Service;

    public function __construct(JolpicaF1Service $f1Service)
    {
        $this->f1Service = $f1Service;
    }
    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));
        if (strlen($q) < 2) return response()->json([]);

        $drivers = Driver::with(['standings' => function ($query) {
                $query->orderByDesc('season')->orderByDesc('round')->limit(1);
            }, 'standings.constructor'])
            ->where(function ($query) use ($q) {
                $query->where('given_name', 'like', "%{$q}%")
                      ->orWhere('family_name', 'like', "%{$q}%")
                      ->orWhere('code', 'like', "%{$q}%")
                      ->orWhereRaw("CONCAT(given_name, ' ', family_name) LIKE ?", ["%{$q}%"]);
            })
            ->limit(6)
            ->get();

        return response()->json($drivers->map(function ($d) {
            $standing   = $d->standings->first();
            $constructor = $standing?->constructor;
            $color = $constructor
                ? config('f1.team_colors.' . $constructor->name, '#e10600')
                : '#e10600';

            return [
                'name'        => $d->given_name . ' ' . $d->family_name,
                'code'        => $d->code,
                'number'      => $d->permanent_number,
                'nationality' => $d->nationality,
                'team'        => $constructor?->name,
                'color'       => $color,
                'url'         => route('drivers.show', $d),
            ];
        }));
    }

    public function syncStandings($year = 2025)
    {
        if (Auth::user()->role !== 'admin') abort(403, 'Unauthorized');

        $response = Http::get("https://api.jolpi.ca/ergast/f1/{$year}/driverstandings/");
        if ($response->failed()) abort(500, 'Unable to fetch standings data');

        $data = $response->json();
        $standingsList = $data['MRData']['StandingsTable']['StandingsLists'][0]['DriverStandings'] ?? null;

        if (!$standingsList) {
            return redirect()->back()->with('error', 'No standings data found for this season.');
        }

        $season = $data['MRData']['StandingsTable']['season'];
        $round  = $data['MRData']['StandingsTable']['round'];

        Standing::where('season', $season)->delete();

        foreach ($standingsList as $entry) {
            $driverData = $entry['Driver'];
            $constructorData = $entry['Constructors'][0];

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

            Standing::create([
                'driver_id' => $driver->id,
                'constructor_id' => $constructor->id,
                'season' => $season,
                'round' => $round,
                'position' => $entry['position'],
                'points' => $entry['points'],
                'wins' => $entry['wins'],
            ]);
        }

        return redirect()->back()->with('success', "Standings for {$season} synced successfully.");
    }

    public function showStandings(Request $request)
    {
        // Get available seasons
        $seasons = $this->f1Service->getSeasons();

        // Get selected season or default to current
        $selectedSeason = $request->get('season', $this->f1Service->getCurrentSeason());

        // Check if we need to sync and redirect with loading message
        $needsSync = !Standing::where('season', $selectedSeason)->exists();

        if ($needsSync && $request->has('season')) {
            // Sync standings for the selected season
            $this->syncStandingsIfNeeded($selectedSeason);

            // Redirect back to the same page to ensure fresh data load
            return redirect()->route('standings.index', ['season' => $selectedSeason])
                ->with('success', "Loading {$selectedSeason} season standings...");
        }

        // Sync if needed (for initial page load without season parameter)
        $this->syncStandingsIfNeeded($selectedSeason);

        // Get standings for the selected season
        $standings = \DB::table('standings')
            ->join('drivers', 'standings.driver_id', '=', 'drivers.id')
            ->join('constructors', 'standings.constructor_id', '=', 'constructors.id')
            ->select(
                'standings.*',
                'drivers.id as driver_id',
                'drivers.given_name',
                'drivers.family_name',
                'drivers.code',
                'drivers.nationality as driver_nationality',
                'constructors.name as constructor_name'
            )
            ->where('standings.season', $selectedSeason)
            ->where('standings.position', '>', 0)
            ->orderBy('standings.position')
            ->get();

        return view('standings.index', [
            'standings' => $standings,
            'season' => $selectedSeason,
            'seasons' => $seasons,
            'selectedSeason' => $selectedSeason,
        ]);
    }

    public function index(Request $request)
    {
        // Get available seasons
        $seasons = $this->f1Service->getSeasons();

        // Get selected season or default to current
        $selectedSeason = $request->get('season', $this->f1Service->getCurrentSeason());

        // Check if we need to sync and redirect with loading message
        $needsSync = !Standing::where('season', $selectedSeason)->exists();

        if ($needsSync && $request->has('season')) {
            // Sync standings for the selected season
            $this->syncStandingsIfNeeded($selectedSeason);

            // Redirect back to the same page to ensure fresh data load
            return redirect()->route('drivers.index', ['season' => $selectedSeason])
                ->with('success', "Loading {$selectedSeason} season data...");
        }

        // Sync if needed (for initial page load without season parameter)
        $this->syncStandingsIfNeeded($selectedSeason);

        // Get drivers with standings for the selected season
        $drivers = Driver::whereHas('standings', function($query) use ($selectedSeason) {
            $query->where('season', $selectedSeason)
                  ->where('position', '>', 0);
        })
        ->with(['standings' => function($query) use ($selectedSeason) {
            $query->where('season', $selectedSeason)
                  ->where('position', '>', 0)
                  ->orderByDesc('round')
                  ->limit(1);
        }, 'standings.constructor'])
        ->get()
        ->sortBy(function($driver) {
            return $driver->standings->first()->position ?? 999;
        });

        return view('drivers.index2', compact('drivers', 'seasons', 'selectedSeason'));
    }

    private function syncStandingsIfNeeded($season)
    {
        // Check if we have standings for this season
        $hasStandings = Standing::where('season', $season)->exists();

        if (!$hasStandings) {
            // Fetch from API
            $standingsData = $this->f1Service->getDriverStandings($season);

            if ($standingsData) {
                $driverStandings = $standingsData['DriverStandings'] ?? [];
                $round = $standingsData['round'];

                foreach ($driverStandings as $entry) {
                    $driverData = $entry['Driver'];
                    $constructorData = $entry['Constructors'][0];

                    $driver = Driver::updateOrCreate(
                        ['driver_id' => $driverData['driverId']],
                        [
                            'code' => $driverData['code'] ?? null,
                            'permanent_number' => $driverData['permanentNumber'] ?? null,
                            'given_name' => $driverData['givenName'],
                            'family_name' => $driverData['familyName'],
                            'date_of_birth' => $driverData['dateOfBirth'] ?? null,
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

                    Standing::updateOrCreate(
                        [
                            'driver_id' => $driver->id,
                            'season' => $season,
                            'round' => $round,
                        ],
                        [
                            'constructor_id' => $constructor->id,
                            'position' => $entry['position'] ?? 0,
                            'points' => $entry['points'] ?? 0,
                            'wins' => $entry['wins'] ?? 0,
                        ]
                    );
                }
            }
        }
    }

    /**
     * Keep this function exactly as you wanted:
     * Fetch all seasons the driver has raced in via API
     */
    public function fetchDriverSeasons(Driver $driver): array
    {
        $races = $this->fetchAllDriverResults($driver->driver_id);
        return collect($races)->pluck('season')->unique()->sortDesc()->values()->toArray();
    }

    private function fetchAllDriverResults(string $driverCode): array
    {
        $allRaces = [];
        $limit = 100;
        $offset = 0;

        while (true) {
            $url = "https://api.jolpi.ca/ergast/f1/drivers/{$driverCode}/results.json";
            $response = Http::timeout(15)->get($url, ['limit' => $limit, 'offset' => $offset]);

            if (!$response->successful()) break;

            $json = $response->json()['MRData'] ?? [];
            $races = $json['RaceTable']['Races'] ?? [];
            $total = (int)($json['total'] ?? 0);

            if (empty($races)) break;

            $allRaces = array_merge($allRaces, $races);
            $offset += $limit;
            if ($offset >= $total) break;

            usleep(150000);
        }

        return $allRaces;
    }

    public function showDriver(Driver $driver)
    {
        $driver->load(['latestStanding.constructor']);

        $currentSeason = now()->year;

        // Try current season first
        $seasonStats = $this->getDriverSeasonStats($driver, $currentSeason);

        // If driver has no data for current season, fall back to their latest season in DB
        if (empty($seasonStats['position']) && empty($seasonStats['points'])) {
            $latestStanding = Standing::where('driver_id', $driver->id)
                ->orderByDesc('season')
                ->orderByDesc('round')
                ->first();

            if ($latestStanding && $latestStanding->season != $currentSeason) {
                $seasonStats = $this->getDriverSeasonStats($driver, (int) $latestStanding->season);
            }
        }

        // Career stats (all-time)
        $careerStats = Cache::remember("driver:{$driver->driver_id}:career", now()->addHours(6), function () use ($driver) {
            $races = $this->fetchAllDriverResults($driver->driver_id);
            $results = collect($races)->map(fn($race) => $race['Results'][0] ?? null)->filter();

            $isWin = fn($res) => isset($res['position']) && (int)$res['position'] === 1;
            $isPodium = fn($res) => isset($res['position']) && (int)$res['position'] <= 3;
            $isPole = fn($res) => isset($res['grid']) && (int)$res['grid'] === 1;
            $isDNF = fn($res) => !empty($res['status']) && strtolower($res['status']) !== 'finished' && !preg_match('/^\+?\d+\s+laps?$/i', $res['status']);

            return [
                'races' => $results->count(),
                'points' => $results->sum(fn($r) => (float)($r['points'] ?? 0)),
                'wins' => $results->filter($isWin)->count(),
                'podiums' => $results->filter($isPodium)->count(),
                'poles' => $results->filter($isPole)->count(),
                'dnfs' => $results->filter($isDNF)->count(),
                'fastest_laps' => $results->filter(fn($r) => isset($r['FastestLap']['rank']) && (string)$r['FastestLap']['rank'] === '1')->count(),
            ];
        });

        return view('drivers.show', compact('driver', 'careerStats', 'seasonStats'));
    }

    public function compareDrivers(Request $request, Driver $driver)
    {
        $seasonA = now()->year; // always current season
        $seasonsB = $this->fetchDriverSeasons($driver);
        $seasonB = $request->get('season_b', $seasonsB[0] ?? $seasonA - 1);

        $statsA = $this->getDriverSeasonStats($driver, $seasonA);
        $statsB = $this->getDriverSeasonStats($driver, $seasonB);

        return view('drivers.compare', compact('driver', 'seasonA', 'seasonB', 'statsA', 'statsB', 'seasonsB'));
    }

    private function getDriverSeasonStats(Driver $driver, int $season): array
    {
        // Try DB first
        $standing = Standing::where('driver_id', $driver->id)
            ->where('season', $season)
            ->orderByDesc('round')
            ->first();

        if ($standing) {
            return [
                'season' => $season,
                'position' => $standing->position,
                'points' => $standing->points,
                'wins' => $standing->wins,
                'entries' => (int)$standing->round,
            ];
        }

        // Fallback to API
        $response = Http::get("https://api.jolpi.ca/ergast/f1/{$season}/drivers/{$driver->driver_id}/driverstandings/");
        if (!$response->successful()) {
            return [
                'season' => $season,
                'position' => null,
                'points' => null,
                'wins' => null,
                'entries' => null,
            ];
        }

        $data = $response->json();
        $driverStanding = $data['MRData']['StandingsTable']['StandingsLists'][0]['DriverStandings'][0] ?? null;

        if (!$driverStanding) {
            return [
                'season' => $season,
                'position' => null,
                'points' => null,
                'wins' => null,
                'entries' => null,
            ];
        }

        return [
            'season' => $season,
            'position' => (int)($driverStanding['position'] ?? null),
            'points' => (float)($driverStanding['points'] ?? null),
            'wins' => (int)($driverStanding['wins'] ?? null),
            'entries' => (int)($data['MRData']['StandingsTable']['round'] ?? null),
        ];
    }
}
