<?php
use App\Http\Controllers\ForumController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\DriverStatsController;
use App\Http\Controllers\F1Controller;
use App\Http\Controllers\RaceGameController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/drivers/sync', [F1Controller::class, 'syncStandings'])->name('drivers.sync');
    Route::get('/drivers', [DriverStatsController::class, 'index'])->name('drivers.index');
    Route::get('/f1/standings/{year?}', [F1Controller::class, 'driverStandings']);
    Route::get('/f1/drivers', [F1Controller::class, 'allDrivers']);
    Route::get('/drivers', [F1Controller::class, 'index'])->name('drivers.index');
    Route::get('/drivers1', [F1Controller::class, 'syncStandings'])->name('drivers1.index');
    Route::get('/drivers1', [F1Controller::class, 'index'])->name('drivers1.index');
    Route::get('/races/sync', [F1Controller::class, 'currentSeasonRaces'])->name('races.sync');
    Route::get('/races', [F1Controller::class, 'showRacesFromDb'])->name('races.index');
    Route::get('/standings', [F1Controller::class, 'showStandings'])->name('standings.index');
    Route::get('/results/sync', [F1Controller::class, 'syncSeasonRaceResults'])->name('results.index');
    Route::get('/races/{season}/{round}', [F1Controller::class, 'raceShow'])
    ->name('races.show');
    Route::get('/standings/constructors/{season}', [F1Controller::class, 'constructorsStandings'])
    ->name('standings.constructors');
    
    Route::get('/forums', [ForumController::class, 'index'])->name('forums.index');
    Route::get('/forums/{race}', [ForumController::class, 'show'])->name('forums.show');
    Route::post('/forums/{race}', [ForumController::class, 'store'])->middleware('auth')->name('forums.store');
    
    Route::get('/posts/{post}', [ForumController::class, 'showPost'])->name('posts.show');
    Route::post('/posts/{post}/comment', [ForumController::class, 'storeComment'])->middleware('auth')->name('posts.comment');
    Route::post('/posts/{post}/like', [ForumController::class, 'toggleLike'])->middleware('auth')->name('posts.like');

    Route::get('/drivers/{driver}', [F1Controller::class, 'showDriver'])->name('drivers.show');

    Route::get('/game', [RaceGameController::class, 'index'])->name('game.index');
    Route::get('/game/play', [RaceGameController::class, 'play'])->name('game.play');
    Route::post('/game/store', [RaceGameController::class, 'storePrediction'])->name('game.storePrediction');
    Route::get('/game/my-predictions', [RaceGameController::class, 'myPredictions'])->name('game.myPredictions');
    Route::get('/game/results', [RaceGameController::class, 'predictionResults'])->name('game.predictionResults');
    
    Route::get('/game/past', [RaceGameController::class, 'pastRaces'])->name('game.pastRaces');
Route::get('/game/play-past', [RaceGameController::class, 'playPast'])->name('game.playPast');

Route::get('/leaderboard', [RaceGameController::class, 'leaderboard'])->name('game.leaderboard');

Route::get('/admin/races', [AdminController::class, 'index'])
        ->name('admin.races.index');

Route::get('/admin/races/{race}/edit', [AdminController::class, 'edit'])
    ->name('admin.races.edit');

// Handle the update submission
Route::put('/admin/races/{race}', [AdminController::class, 'update'])
    ->name('admin.races.update');
    Route::get('/admin/panel', [AdminController::class, 'panel'])
        ->name('admin.panel');

    Route::get('/admin/data', [AdminController::class, 'data'])
        ->name('admin.data');
});

require __DIR__.'/auth.php';
