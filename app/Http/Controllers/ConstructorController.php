<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\JolpicaF1Service;
use App\Models\Constructor;
use App\Models\Standing;

class ConstructorController extends Controller
{
    protected $f1Service;

    public function __construct(JolpicaF1Service $f1Service)
    {
        $this->f1Service = $f1Service;
    }

    public function constructorsStandings(Request $request, $season = null)
    {
        // Get available seasons
        $seasons = $this->f1Service->getSeasons();

        // Get selected season from request or route parameter, default to current
        $selectedSeason = $request->get('season', $season ?? $this->f1Service->getCurrentSeason());

        // Check if we need to sync and redirect with loading message
        $needsSync = !Standing::where('season', $selectedSeason)->exists();

        if ($needsSync && ($request->has('season') || $season)) {
            // Sync constructor standings if needed
            $this->syncConstructorStandingsIfNeeded($selectedSeason);

            // Redirect back to the same page to ensure fresh data load
            return redirect()->route('standings.constructors', ['season' => $selectedSeason])
                ->with('success', "Loading {$selectedSeason} season constructor standings...");
        }

        // Sync if needed (for initial page load)
        $this->syncConstructorStandingsIfNeeded($selectedSeason);

        $standings = DB::table('standings')
            ->join('constructors', 'standings.constructor_id', '=', 'constructors.id')
            ->select(
                'constructors.name as constructor_name',
                'constructors.nationality as constructor_nationality',
                DB::raw('SUM(standings.points) as points'),
                DB::raw('SUM(standings.wins) as wins')
            )
            ->where('standings.season', $selectedSeason)
            ->where('standings.position', '>', 0)
            ->groupBy('constructors.name', 'constructors.nationality')
            ->orderByDesc('points')
            ->get();

        return view('standings.constructors', [
            'standings' => $standings,
            'season' => $selectedSeason,
            'seasons' => $seasons,
            'selectedSeason' => $selectedSeason,
        ]);
    }

    private function syncConstructorStandingsIfNeeded($season)
    {
        // Check if we have constructor standings for this season
        $hasStandings = Standing::where('season', $season)->exists();

        if (!$hasStandings) {
            // Fetch driver standings from API (which includes constructor info)
            $standingsData = $this->f1Service->getDriverStandings($season);

            if ($standingsData) {
                $driverStandings = $standingsData['DriverStandings'] ?? [];
                $round = $standingsData['round'];

                foreach ($driverStandings as $entry) {
                    $driverData = $entry['Driver'];
                    $constructorData = $entry['Constructors'][0];

                    $driver = \App\Models\Driver::updateOrCreate(
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
}
?>