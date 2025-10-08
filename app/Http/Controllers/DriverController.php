<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Driver;
use App\Models\Constructor;
use App\Models\Standing;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
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

    public function index()
    {
        $drivers = Driver::with(['latestStanding.constructor'])->get();
        return view('drivers.index2', compact('drivers'));
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

        // Use API first, DB second for current season stats
        $seasonStats = $this->getDriverSeasonStats($driver, $currentSeason);

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
