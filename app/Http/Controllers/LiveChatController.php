<?php

namespace App\Http\Controllers;

use App\Models\Race;
use Illuminate\Support\Facades\Auth;

class LiveChatController extends Controller
{
    public function index()
    {
        // Grab all 2025 races ordered by date for the race selector
        $races = Race::where('season', 2025)
            ->orderBy('round')
            ->get(['id', 'name', 'round', 'date', 'season']);

        // Default to the most recent past race (or first upcoming)
        $defaultRace = $races->filter(fn($r) => now()->gte($r->date))->last()
                    ?? $races->first();

        // Current user's team color
        $chatUserColor = '#e10600';
        if (Auth::check()) {
            $user = Auth::user()->loadMissing('favoriteConstructor');
            $constructorName = $user->favoriteConstructor->name ?? null;
            if ($constructorName) {
                $chatUserColor = config('f1.team_colors.' . $constructorName, '#e10600');
            }
        }

        return view('live-chat', [
            'races'         => $races,
            'defaultRace'   => $defaultRace,
            'chatUserColor' => $chatUserColor,
        ]);
    }
}
