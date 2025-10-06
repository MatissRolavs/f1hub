<?php

namespace App\Http\Controllers;

use App\Models\GameScore;
use App\Models\RacePrediction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class RaceGameController extends Controller
{
    
    public function index()
    {
        $season = date('Y');
        $url = "https://api.jolpi.ca/ergast/f1/{$season}.json";
        $response = Http::timeout(15)->get($url);
    
        $races = collect($response->json()['MRData']['RaceTable']['Races'] ?? [])
            ->filter(fn($race) => \Carbon\Carbon::parse($race['date'])->isFuture())
            ->sortBy('date')
            ->take(1)
            ->values();
    
        // Fetch leaderboard data
        $leaderboard = DB::table('game_scores')
            ->select('player_name', DB::raw('SUM(score) as total_score'), DB::raw('COUNT(*) as races_played'))
            ->groupBy('player_name')
            ->orderByDesc('total_score')
            ->get();
    
        return view('game.index', compact('races', 'leaderboard'));
    }
    


   
    public function play(Request $request)
    {
        $season = $request->input('season');
        $round  = (int) $request->input('round');
    
    
        $prevRound = $round > 1 ? $round - 1 : 1;
        $url = "https://api.jolpi.ca/ergast/f1/{$season}/{$prevRound}/results.json";
        $response = Http::timeout(15)->get($url);
    
        if (!$response->successful()) {
            return back()->with('error', 'Unable to fetch previous race driver list.');
        }
    
        $results = $response->json()['MRData']['RaceTable']['Races'][0]['Results'] ?? [];
        if (empty($results)) {
            return back()->with('error', 'No previous race results found.');
        }
    
        $drivers = collect($results)->map(function ($res) {
            return (object)[
                'id' => $res['Driver']['driverId'],
                'given_name' => $res['Driver']['givenName'],
                'family_name' => $res['Driver']['familyName'],
            ];
        });
    
        // Check if user already has a prediction
        $savedPrediction = RacePrediction::where('user_id', auth()->id())
            ->where('season', $season)
            ->where('round', $round)
            ->first();
    
        $savedOrder = $savedPrediction ? json_decode($savedPrediction->predicted_order, true) : [];
    
        $raceName = "{$season} Round {$round}";
    
        return view('game.play', [
            'drivers' => $drivers,
            'raceName' => $raceName,
            'season' => $season,
            'round' => $round,
            'savedOrder' => $savedOrder
        ]);
    }
    

    public function storePrediction(Request $request)
    {
        $positions = $request->input('positions', []);

        if (in_array('', $positions, true)) {
            return back()->with('error', 'Please select a driver for every position.');
        }
        if (count($positions) !== count(array_unique($positions))) {
            return back()->with('error', 'Each driver can only be assigned to one position.');
        }

        ksort($positions);
        $predictedOrder = array_values($positions);

        RacePrediction::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'season' => $request->input('season'),
                'round' => $request->input('round'),
            ],
            [
                'predicted_order' => json_encode($predictedOrder),
            ]
        );

        return redirect()->route('game.index')->with('success', 'Prediction saved! You can change it until race day.');
    }

    public function scoreRace($season, $round)
    {
        $url = "https://api.jolpi.ca/ergast/f1/{$season}/{$round}/results.json";
        $results = Http::timeout(15)->get($url)->json()['MRData']['RaceTable']['Races'][0]['Results'] ?? [];

        if (empty($results)) {
            return back()->with('error', 'No results available yet.');
        }

        $actualOrder = collect($results)->pluck('Driver.driverId')->values()->toArray();

        $predictions = RacePrediction::where('season', $season)->where('round', $round)->get();

        foreach ($predictions as $prediction) {
            $predictedOrder = json_decode($prediction->predicted_order, true);
            $score = 0;
            foreach ($predictedOrder as $i => $driverId) {
                if (($actualOrder[$i] ?? null) == $driverId) {
                    $score++;
                }
            }

            GameScore::updateOrCreate(
                [
                    'race_id' => null,
                    'race_name' => "{$season} Round {$round}",
                    'player_name' => $prediction->user->name,
                ],
                [
                    'score' => $score,
                    'total' => count($actualOrder),
                ]
            );
        }

        return back()->with('success', 'Predictions scored!');
    }
    public function myPredictions()
{
    $predictions = RacePrediction::where('user_id', Auth::id())->get();

    $season = date('Y');
    $url = "https://api.jolpi.ca/ergast/f1/{$season}.json";
    $races = collect(Http::timeout(15)->get($url)->json()['MRData']['RaceTable']['Races'] ?? []);

    $predictions = $predictions->map(function($pred) use ($races) {
        $raceInfo = $races->firstWhere('round', $pred->round);
        $pred->raceName = $raceInfo['raceName'] ?? "{$pred->season} Round {$pred->round}";
        $pred->raceDate = $raceInfo['date'] ?? null;

        // Convert driver IDs to names with positions
        $order = json_decode($pred->predicted_order, true) ?? [];
        $pred->formatted_order = collect($order)->map(function($driverId, $index) {
            $driver = \App\Models\Driver::where('driver_id', $driverId)->first();
            if ($driver) {
                return ($index + 1) . '. ' . $driver->given_name . ' ' . $driver->family_name;
            }
            return ($index + 1) . '. ' . $driverId; // fallback if driver not found
        })->toArray();

        return $pred;
    });

    return view('game.my-predictions', compact('predictions'));
}

public function predictionResults()
{
    $scores = GameScore::where('player_name', Auth::user()->name)
        ->orderByDesc('created_at')
        ->get();

    return view('game.prediction-results', compact('scores'));
}
public function pastRaces()
{
    $season = date('Y');
    $url = "https://api.jolpi.ca/ergast/f1/{$season}.json";
    $response = Http::timeout(15)->get($url);

    if (!$response->successful()) {
        return back()->with('error', 'Unable to fetch race list.');
    }

    $races = collect($response->json()['MRData']['RaceTable']['Races'] ?? [])
        ->filter(fn($race) => \Carbon\Carbon::parse($race['date'])->isPast())
        ->values();

    return view('game.past-races', compact('races'));
}

public function playPast(Request $request)
{
    $season = $request->input('season');
    $round  = (int) $request->input('round');

    $url = "https://api.jolpi.ca/ergast/f1/{$season}/{$round}/results.json";
    $response = Http::timeout(15)->get($url);

    if (!$response->successful()) {
        return back()->with('error', 'Unable to fetch driver list.');
    }

    $results = $response->json()['MRData']['RaceTable']['Races'][0]['Results'] ?? [];
    if (empty($results)) {
        return back()->with('error', 'No results found for this race.');
    }

    $drivers = collect($results)->map(fn($res) => (object)[
        'id' => $res['Driver']['driverId'],
        'given_name' => $res['Driver']['givenName'],
        'family_name' => $res['Driver']['familyName'],
    ]);

    $raceName = "{$season} Round {$round}";

    return view('game.play', [
        'drivers' => $drivers,
        'raceName' => $raceName,
        'season' => $season,
        'round' => $round,
        'savedOrder' => []
    ]);
}
public function leaderboard()
{
    $leaderboard = DB::table('game_scores')
        ->select('player_name', DB::raw('SUM(score) as total_score'), DB::raw('COUNT(*) as races_played'))
        ->groupBy('player_name')
        ->orderByDesc('total_score')
        ->get();

    return view('game.leaderboard', compact('leaderboard'));
}

}
