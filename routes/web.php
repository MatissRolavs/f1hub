<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\DriverStatsController;
use App\Http\Controllers\F1Controller;
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
    Route::get('/drivers/sync', [F1Controller::class, 'syncDrivers'])->name('drivers.sync');
    Route::get('/drivers', [DriverStatsController::class, 'index'])->name('drivers.index');
    Route::get('/drivers/{competitorId}', [DriverStatsController::class, 'show'])->name('drivers.show');
    Route::get('/f1/standings/{year?}', [F1Controller::class, 'driverStandings']);
    Route::get('/f1/drivers', [F1Controller::class, 'allDrivers']);
    Route::get('/drivers', [F1Controller::class, 'index'])->name('drivers.index');
    
});

require __DIR__.'/auth.php';
