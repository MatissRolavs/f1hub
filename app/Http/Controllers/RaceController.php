<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Services\JolpicaF1Service;
use App\Models\Driver;
use App\Models\Constructor;
use App\Models\Race;
use App\Models\Race_result;
use App\Models\GameScore;
use App\Models\RacePrediction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RaceController extends Controller
{
    protected $f1Service;

    public function __construct(JolpicaF1Service $f1Service)
    {
        $this->f1Service = $f1Service;
    }
    

    public function currentSeasonRaces()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        set_time_limit(0);

        $seasons = $this->f1Service->getSeasons();
        $currentSeason = (string) now()->year;

        // Past seasons already in the DB are treated as complete — skip.
        // Current season is always re-fetched so schedule changes + new rounds come through.
        $syncedSeasons = Race::query()
            ->select('season')
            ->distinct()
            ->pluck('season')
            ->map(fn($s) => (string) $s)
            ->all();

        $seasonsProcessed = 0;
        $seasonsSkipped   = 0;
        $totalInserted    = 0;
        $totalPruned      = 0;

        foreach ($seasons as $year) {
            $year = (string) $year;

            if ($year !== $currentSeason && in_array($year, $syncedSeasons, true)) {
                $seasonsSkipped++;
                continue;
            }

            $response = Http::get("https://api.jolpi.ca/ergast/f1/{$year}.json");
            if ($response->failed()) {
                \Log::error("Failed to fetch races for {$year}");
                continue;
            }

            $races = $response->json()['MRData']['RaceTable']['Races'] ?? [];
            if (empty($races)) {
                continue;
            }

            $apiRounds = [];
            foreach ($races as $race) {
                $apiRounds[] = (int) $race['round'];

                $time = null;
                if (!empty($race['time'])) {
                    $time = Carbon::parse($race['date'] . 'T' . $race['time'])->toTimeString();
                }

                $record = Race::updateOrCreate(
                    [
                        'season' => $year,
                        'round'  => $race['round'],
                    ],
                    [
                        'name'         => $race['raceName'],
                        'date'         => $race['date'],
                        'time'         => $time,
                        'circuit_name' => $race['Circuit']['circuitName'] ?? null,
                        'locality'     => $race['Circuit']['Location']['locality'] ?? null,
                        'country'      => $race['Circuit']['Location']['country'] ?? null,
                        'url'          => $race['url'] ?? null,
                    ]
                );

                if ($record->wasRecentlyCreated) {
                    $totalInserted++;
                }
            }

            // Prune rounds the API no longer exposes for this season.
            $totalPruned += Race::where('season', $year)
                ->whereNotIn('round', $apiRounds)
                ->delete();

            $seasonsProcessed++;
        }

        $msg = "Races sync complete. Processed {$seasonsProcessed} season(s), skipped {$seasonsSkipped} already-saved. Inserted {$totalInserted} new race(s).";
        if ($totalPruned > 0) {
            $msg .= " Removed {$totalPruned} orphaned round(s).";
        }
        return redirect()->back()->with('success', $msg);
    }
    public function showRacesFromDb(Request $request)
    {
        // Get available seasons
        $seasons = $this->f1Service->getSeasons();

        // Get selected season or default to current
        $selectedSeason = $request->get('season', $this->f1Service->getCurrentSeason());

        // Get races for the selected season (admins populate the DB via the
        // "Sync Current Season Races" button; no lazy loading here).
        $races = Race::where('season', $selectedSeason)->orderBy('date')->get();

        $f1Images = [
            'https://running-riversport.com/wp-content/uploads/2022/09/4-Best-F1-tracks.jpg',
            'https://www.cmcmotorsports.com/cdn/shop/articles/f1-cars-corner_5a8d606f-ff31-4542-917c-c4791f88ec49_1024x.jpg?v=1741191660',
            'https://t3.ftcdn.net/jpg/13/22/58/86/360_F_1322588670_REIoCPfaSiVcN7ZibFuYeZIfdVQVBEZL.jpg',
            'https://www.topgear.com/sites/default/files/news-listicle/image/2022/06/0-Best-F1-tracks.jpg',
            'https://static.independent.co.uk/s3fs-public/thumbnails/image/2018/05/18/12/formula-1.jpg?width=1200&height=630&fit=crop',
            'https://cdn.racingnews365.com/_1800x945_crop_center-center_75_none/E_BeHUFX0AA0QLs.jpeg?v=1673948090',
        ];

        $prepare = function ($race, $label) use ($f1Images) {
            if (!$race) return null;
        
            $raceDate = Carbon::parse($race->date);
            $status = $raceDate->isPast() ? 'Completed' : 'Upcoming';
            $statusClass = $raceDate->isPast() ? 'bg-green-600' : 'bg-yellow-500';
            $image = $race->track_image ?: $f1Images[array_rand($f1Images)];
        
            $top3 = [];
            if ($status === 'Completed') {
                $results = \App\Models\Race_result::with(['driver','constructor'])
                    ->where('season', $race->season)
                    ->where('round', $race->round)
                    ->orderBy('position')
                    ->limit(3)
                    ->get();
        
                foreach ($results as $res) {
                    $top3[] = [
                        'driver' => $res->driver->given_name . ' ' . $res->driver->family_name,
                        'team'   => $res->constructor->name,
                        'time'   => $res->race_time ?? $res->status,
                    ];
                }
            }
        
            return [
                'label'        => $label,
                'name'         => $race->name,
                'date'         => $raceDate->format('D, d M Y'),
                'location'     => "{$race->locality}, {$race->country}",
                'status'       => $status,
                'statusClass'  => $statusClass,
                'img'          => $image,
                'length'       => $race->track_length,
                'turns'        => $race->turns,
                'lapRecord'    => $race->lap_record,
                'description'  => $race->description,
                'resultsUrl'   => route('races.show', ['season' => $race->season, 'round' => $race->round]),
                'top3'         => $top3,
            ];
        };

        $previousRace = $prepare(
            $races->filter(fn($r) => Carbon::parse($r->date)->isPast())->sortByDesc('date')->first(),
            'Previous Race'
        );

        $nextRace = $prepare(
            $races->filter(fn($r) => Carbon::parse($r->date)->isFuture())->sortBy('date')->first(),
            'Next Race'
        );

        $upcomingRace = $prepare(
            $races->filter(fn($r) => Carbon::parse($r->date)->isFuture())->sortBy('date')->skip(1)->first(),
            'Upcoming Race'
        );

        $allRaces = $races->map(function ($race) use ($prepare) {
            return $prepare($race, null);
        });

        return view('races.index', [
            'featuredRaces' => array_filter([$previousRace, $nextRace, $upcomingRace]),
            'allRaces'      => $allRaces,
            'seasons'       => $seasons,
            'selectedSeason' => $selectedSeason,
        ]);
    }

public function syncSeasonRaceResults()
{
    if (Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized');
    }

    set_time_limit(0);

    $seasons = $this->f1Service->getSeasons();

    $totalInserted = 0;
    $seasonsProcessed = 0;

    \Log::info("=== Starting race results sync across all seasons ===");

    foreach ($seasons as $year) {
        $year = (string) $year;

        \Log::info("Syncing race results for {$year}");

        $limit = 100;
        $offset = 0;
        $allRaces = [];
        $total = 0;

        do {
            $response = Http::get("https://api.jolpi.ca/ergast/f1/{$year}/results.json", [
                'limit'  => $limit,
                'offset' => $offset,
            ]);

            if ($response->failed()) {
                \Log::error("API request failed for {$year} at offset {$offset}");
                break;
            }

            $data  = $response->json();
            $total = (int) ($data['MRData']['total'] ?? 0);
            $races = $data['MRData']['RaceTable']['Races'] ?? [];

            if (empty($races)) break;

            $allRaces = array_merge($allRaces, $races);
            $offset  += $limit;
        } while (count($allRaces) < $total);

        if (empty($allRaces)) {
            continue;
        }

        foreach ($allRaces as $race) {
            foreach ($race['Results'] as $result) {
                try {
                    $driverData      = $result['Driver'];
                    $constructorData = $result['Constructor'];

                    $driver = Driver::updateOrCreate(
                        ['driver_id' => $driverData['driverId']],
                        [
                            'code'             => $driverData['code'] ?? null,
                            'permanent_number' => $driverData['permanentNumber'] ?? null,
                            'given_name'       => $driverData['givenName'],
                            'family_name'      => $driverData['familyName'],
                            'date_of_birth'    => $driverData['dateOfBirth'] ?? null,
                            'nationality'      => $driverData['nationality'] ?? null,
                            'url'              => $driverData['url'] ?? null,
                        ]
                    );

                    $constructor = Constructor::updateOrCreate(
                        ['constructor_id' => $constructorData['constructorId']],
                        [
                            'name'        => $constructorData['name'],
                            'nationality' => $constructorData['nationality'] ?? null,
                            'url'         => $constructorData['url'] ?? null,
                        ]
                    );

                    $raceResult = Race_result::firstOrCreate(
                        [
                            'season'    => $race['season'],
                            'round'     => $race['round'],
                            'driver_id' => $driver->id,
                        ],
                        [
                            'constructor_id'     => $constructor->id,
                            'race_name'          => $race['raceName'],
                            'date'               => $race['date'] ?? null,
                            'time'               => !empty($race['time']) ? Carbon::parse($race['date'] . 'T' . $race['time'])->toTimeString() : null,
                            'grid'               => $result['grid'] ?? null,
                            'position'           => is_numeric($result['position'] ?? null) ? (int) $result['position'] : null,
                            'position_text'      => $result['positionText'] ?? null,
                            'points'             => $result['points'] ?? 0,
                            'laps'               => $result['laps'] ?? 0,
                            'status'             => $result['status'] ?? null,
                            'race_time'          => $result['Time']['time'] ?? null,
                            'fastest_lap_time'   => $result['FastestLap']['Time']['time'] ?? null,
                            'fastest_lap_rank'   => $result['FastestLap']['rank'] ?? null,
                            'fastest_lap_speed'  => $result['FastestLap']['AverageSpeed']['speed'] ?? null,
                        ]
                    );

                    if ($raceResult->wasRecentlyCreated) {
                        $totalInserted++;
                    }
                } catch (\Exception $e) {
                    \Log::error("Insert failed for {$race['raceName']}: " . $e->getMessage());
                }
            }

            if (!empty($race['date']) && Carbon::parse($race['date'])->isPast()) {
                $this->scorePredictionsForRace($race['season'], $race['round'], $race['raceName']);
            }
        }

        $seasonsProcessed++;
    }

    $msg = "Sync complete. Processed {$seasonsProcessed} season(s). Inserted {$totalInserted} new result rows (existing rows skipped).";
    return redirect()->back()->with('success', $msg);
}
protected function scorePredictionsForRace($season, $round, $raceName)
{
    $actualOrder = Race_result::where('season', $season)
        ->where('round', $round)
        ->orderBy('position')
        ->with('driver')
        ->get()
        ->pluck('driver.driver_id')
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


}
?>