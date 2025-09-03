<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenF1Service
{
    protected $baseUrl = 'https://api.openf1.org/v1/';

    public function getDrivers($filters = [])
    {
        return Http::get($this->baseUrl . 'drivers', $filters)->json();
    }

    public function getSessions($filters = [])
    {
        return Http::get($this->baseUrl . 'sessions', $filters)->json();
    }

    public function getLaps($filters = [])
    {
        return Http::get($this->baseUrl . 'laps', $filters)->json();
    }

    public function getPositions($filters = [])
    {
        return Http::get($this->baseUrl . 'positions', $filters)->json();
    }

    
}

?>