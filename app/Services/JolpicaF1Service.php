<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class JolpicaF1Service
{
    protected $baseUrl = 'http://api.jolpi.ca/ergast/f1/';

    public function getDriverStandings($season = 'current')
    {
        $url = $this->baseUrl . "{$season}/driverstandings.json";
        return Http::get($url)->json();
    }

    public function getRaceSchedule($season = 'current')
    {
        $url = $this->baseUrl . "{$season}.json";
        return Http::get($url)->json();
    }

    public function getRaceResults($season, $round)
    {
        $url = $this->baseUrl . "{$season}/{$round}/results.json";
        return Http::get($url)->json();
    }
}

?>