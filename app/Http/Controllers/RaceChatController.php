<?php

namespace App\Http\Controllers;

use App\Events\RaceChatMessage;
use App\Events\RaceChatModeration;
use App\Models\ChatMute;
use App\Models\User;
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

        // Block muted users
        if (ChatMute::isMuted($user->id)) {
            $mute = ChatMute::where('user_id', $user->id)
                ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
                ->first();

            $message = $mute->expires_at
                ? 'You are timed out until ' . $mute->expires_at->format('H:i') . '.'
                : 'You are permanently muted from chat.';

            return response()->json(['muted' => true, 'message' => $message], 403);
        }

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

    public function mute(Request $request)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $request->validate([
            'username'   => ['required', 'string'],
            'expires_in' => ['nullable', 'integer', 'min:1'], // seconds, null = permanent
        ]);

        $target = User::where('name', $request->username)->firstOrFail();

        // Remove any existing mute first
        ChatMute::where('user_id', $target->id)->delete();

        $expiresAt = $request->expires_in
            ? now()->addSeconds($request->expires_in)
            : null;

        ChatMute::create([
            'user_id'    => $target->id,
            'muted_by'   => Auth::id(),
            'expires_at' => $expiresAt,
        ]);

        broadcast(new RaceChatModeration(
            action:    'muted',
            userId:    $target->id,
            username:  $target->name,
            expiresIn: $request->expires_in,
        ));

        return response()->json(['ok' => true]);
    }

    public function unmute(Request $request)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $request->validate(['username' => ['required', 'string']]);

        $target = User::where('name', $request->username)->firstOrFail();

        ChatMute::where('user_id', $target->id)->delete();

        broadcast(new RaceChatModeration(
            action:    'unmuted',
            userId:    $target->id,
            username:  $target->name,
            expiresIn: null,
        ));

        return response()->json(['ok' => true]);
    }
}
