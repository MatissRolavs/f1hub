<?php

namespace App\Http\Controllers;

use App\Services\SportradarService;

class DriverStatsController extends Controller
{
    protected $sportradar;

    public function __construct(SportradarService $sportradar)
    {
        $this->sportradar = $sportradar;
    }

    public function show($competitorId)
    {
        $driver = $this->sportradar->getCompetitorProfile($competitorId);

        if (!$driver) {
            abort(404, 'Driver not found or API error.');
        }

        return view('drivers.show', compact('driver'));
    }

    public function index()
{
    $drivers = $this->sportradar->getCurrentSeasonDrivers();
    return view('drivers.index', compact('drivers'));
}

}
