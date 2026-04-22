<?php

namespace App\Http\Controllers;

use App\Events\RaceChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RaceChatController extends Controller
{
    public function send(Request $request, string $season, string $round)
    {
        $request->validate([
            'message' => ['required', 'string', 'min:1', 'max:300'],
        ]);

        $user = Auth::user();

        // Resolve favourite team color
        $teamColor = '#e10600';
        if ($user->favorite_constructor_id) {
            $user->loadMissing('favoriteConstructor');
            $name = $user->favoriteConstructor->name ?? null;
            if ($name) {
                $teamColor = config('f1.team_colors.' . $name, '#e10600');
            }
        }

        broadcast(new RaceChatMessage(
            raceKey:   "{$season}_{$round}",
            username:  $user->name,
            message:   $request->message,
            teamColor: $teamColor,
            timestamp: now()->format('H:i'),
        ))->toOthers();

        return response()->json(['ok' => true]);
    }
}
