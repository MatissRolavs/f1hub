<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
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
    

    public function currentSeasonRaces()
    {   
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        else{
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
    }
    public function showRacesFromDb()
    {
        $races = Race::orderBy('date')->get();

        $f1Images = [
            'https://running-riversport.com/wp-content/uploads/2022/09/4-Best-F1-tracks.jpg',
            'https://www.cmcmotorsports.com/cdn/shop/articles/f1-cars-corner_5a8d606f-ff31-4542-917c-c4791f88ec49_1024x.jpg?v=1741191660',
            'https://t3.ftcdn.net/jpg/13/22/58/86/360_F_1322588670_REIoCPfaSiVcN7ZibFuYeZIfdVQVBEZL.jpg',
            'https://www.topgear.com/sites/default/files/news-listicle/image/2022/06/0-Best-F1-tracks.jpg',
            'https://static.independent.co.uk/s3fs-public/thumbnails/image/2018/05/18/12/formula-1.jpg?width=1200&height=630&fit=crop',
            'https://cdn.racingnews365.com/_1800x945_crop_center-center_75_none/E_BeHUFX0AA0QLs.jpeg?v=1673948090',
        ];

        // Helper to prepare race card data
        $prepare = function ($race, $label) use ($f1Images) {
            if (!$race) return null;
        
            $raceDate = Carbon::parse($race->date);
            $status = $raceDate->isPast() ? 'Completed' : 'Upcoming';
            $statusClass = $raceDate->isPast() ? 'bg-green-600' : 'bg-yellow-500';
            $image = $race->track_image ?: $f1Images[array_rand($f1Images)];
        
            // 🔑 Fetch top 3 results
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
                'top3'         => $top3, // ✅ attach top 3
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
        ]);
    }

public function syncSeasonRaceResults($year = 2025)
{   
    if (Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized');
    }
    else{
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


}
?>