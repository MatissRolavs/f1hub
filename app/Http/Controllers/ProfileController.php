<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Constructor;
use App\Models\Standing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $season = now()->year;

        $seasonStandings = Standing::where('season', $season)
            ->where('position', '>', 0)
            ->with(['driver', 'constructor'])
            ->get()
            ->unique(fn ($s) => $s->driver_id . '-' . $s->constructor_id)
            ->values();

        $constructors = $seasonStandings
            ->pluck('constructor')
            ->unique('id')
            ->sortBy('name')
            ->values();

        $driversByConstructor = $seasonStandings
            ->groupBy('constructor_id')
            ->map(fn ($rows) => $rows
                ->map(fn ($s) => [
                    'id' => $s->driver->id,
                    'name' => trim($s->driver->given_name . ' ' . $s->driver->family_name),
                    'number' => $s->driver->permanent_number,
                ])
                ->sortBy('name')
                ->values()
            );

        return view('profile.edit', [
            'user' => $request->user(),
            'favoriteConstructors' => $constructors,
            'driversByConstructor' => $driversByConstructor,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
