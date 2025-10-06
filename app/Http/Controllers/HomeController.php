<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Race;
use App\Models\Race_result;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
{
    // Drivers preview
    $drivers = Driver::with('latestStanding.constructor')
        ->take(3)
        ->get();

    // Featured races (custom cards)
    $featuredRaces = $this->getFeaturedRaces();

    // --- Pull Next Race from Ergast (just like game.index) ---
    $season = date('Y');
    $url = "https://api.jolpi.ca/ergast/f1/{$season}.json";
    $response = Http::timeout(15)->get($url);

    $nextRace = collect($response->json()['MRData']['RaceTable']['Races'] ?? [])
        ->filter(fn($race) => \Carbon\Carbon::parse($race['date'])->isFuture())
        ->sortBy('date')
        ->first(); // get only the very next race

    return view('dashboard', [
        'drivers'       => $drivers,
        'featuredRaces' => $featuredRaces,
        'nextRace'      => $nextRace,
    ]);
}

    public function getFeaturedRaces()
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

        $prepare = function ($race, $label) use ($f1Images) {
            if (!$race) return null;

            $raceDate = Carbon::parse($race->date);
            $status = $raceDate->isPast() ? 'Completed' : 'Upcoming';
            $statusClass = $raceDate->isPast() ? 'bg-green-600' : 'bg-yellow-500';
            $image = $race->track_image ?: $f1Images[array_rand($f1Images)];

            $top3 = [];
            if ($status === 'Completed') {
                $results = Race_result::with(['driver','constructor'])
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
                'time'        => $race->time ?? '00:00:00',
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

       
        return array_filter([$previousRace, $nextRace, $upcomingRace]);
    }
}
