<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class JolpicaF1Service
{
    private const BASE_URL = 'https://api.jolpi.ca/ergast/f1';
    private const TIMEOUT = 15;
    private const RATE_LIMIT_DELAY = 150000; // 150ms in microseconds

    /**
     * Get all available F1 seasons
     */
    public function getSeasons(): array
    {
        return Cache::remember('f1:seasons', now()->addDays(7), function () {
            $response = Http::timeout(self::TIMEOUT)->get(self::BASE_URL . '/seasons.json', [
                'limit' => 100,
            ]);

            if (!$response->successful()) {
                return [];
            }

            $data = $response->json();
            $seasons = $data['MRData']['SeasonTable']['Seasons'] ?? [];

            return collect($seasons)
                ->pluck('season')
                ->sort()
                ->reverse()
                ->values()
                ->toArray();
        });
    }

    /**
     * Get driver standings for a specific season
     */
    public function getDriverStandings(string $season = 'current'): ?array
    {
        $response = Http::timeout(self::TIMEOUT)
            ->get(self::BASE_URL . "/{$season}/driverstandings.json");

        if (!$response->successful()) {
            return null;
        }

        $data = $response->json();
        return $data['MRData']['StandingsTable']['StandingsLists'][0] ?? null;
    }

    /**
     * Get constructor standings for a specific season
     */
    public function getConstructorStandings(string $season = 'current'): ?array
    {
        $response = Http::timeout(self::TIMEOUT)
            ->get(self::BASE_URL . "/{$season}/constructorstandings.json");

        if (!$response->successful()) {
            return null;
        }

        $data = $response->json();
        return $data['MRData']['StandingsTable']['StandingsLists'][0] ?? null;
    }

    /**
     * Get all drivers for a specific season
     */
    public function getDriversBySeason(string $season = 'current'): array
    {
        $response = Http::timeout(self::TIMEOUT)
            ->get(self::BASE_URL . "/{$season}/drivers.json");

        if (!$response->successful()) {
            return [];
        }

        $data = $response->json();
        return $data['MRData']['DriverTable']['Drivers'] ?? [];
    }

    /**
     * Get all races for a specific season
     */
    public function getRacesBySeason(string $season = 'current'): array
    {
        $response = Http::timeout(self::TIMEOUT)
            ->get(self::BASE_URL . "/{$season}.json");

        if (!$response->successful()) {
            return [];
        }

        $data = $response->json();
        return $data['MRData']['RaceTable']['Races'] ?? [];
    }

    /**
     * Get all race results for a specific season (paginated)
     */
    public function getSeasonRaceResults(string $season): array
    {
        $allResults = [];
        $limit = 100;
        $offset = 0;

        while (true) {
            $response = Http::timeout(self::TIMEOUT)
                ->get(self::BASE_URL . "/{$season}/results.json", [
                    'limit' => $limit,
                    'offset' => $offset,
                ]);

            if (!$response->successful()) {
                break;
            }

            $data = $response->json()['MRData'] ?? [];
            $races = $data['RaceTable']['Races'] ?? [];
            $total = (int)($data['total'] ?? 0);

            if (empty($races)) {
                break;
            }

            $allResults = array_merge($allResults, $races);
            $offset += $limit;

            if ($offset >= $total) {
                break;
            }

            usleep(self::RATE_LIMIT_DELAY);
        }

        return $allResults;
    }

    /**
     * Get specific race results by season and round
     */
    public function getRaceResults(string $season, string $round): ?array
    {
        $response = Http::timeout(self::TIMEOUT)
            ->get(self::BASE_URL . "/{$season}/{$round}/results.json");

        if (!$response->successful()) {
            return null;
        }

        $data = $response->json();
        $races = $data['MRData']['RaceTable']['Races'] ?? [];

        return $races[0] ?? null;
    }

    /**
     * Get all results for a specific driver (paginated)
     */
    public function getDriverResults(string $driverId): array
    {
        $allRaces = [];
        $limit = 100;
        $offset = 0;

        while (true) {
            $response = Http::timeout(self::TIMEOUT)
                ->get(self::BASE_URL . "/drivers/{$driverId}/results.json", [
                    'limit' => $limit,
                    'offset' => $offset,
                ]);

            if (!$response->successful()) {
                break;
            }

            $json = $response->json()['MRData'] ?? [];
            $races = $json['RaceTable']['Races'] ?? [];
            $total = (int)($json['total'] ?? 0);

            if (empty($races)) {
                break;
            }

            $allRaces = array_merge($allRaces, $races);
            $offset += $limit;

            if ($offset >= $total) {
                break;
            }

            usleep(self::RATE_LIMIT_DELAY);
        }

        return $allRaces;
    }

    /**
     * Get driver standings for a specific driver in a season
     */
    public function getDriverSeasonStandings(string $season, string $driverId): ?array
    {
        $response = Http::timeout(self::TIMEOUT)
            ->get(self::BASE_URL . "/{$season}/drivers/{$driverId}/driverstandings.json");

        if (!$response->successful()) {
            return null;
        }

        $data = $response->json();
        $standingsList = $data['MRData']['StandingsTable']['StandingsLists'][0] ?? null;

        if (!$standingsList) {
            return null;
        }

        return [
            'season' => $standingsList['season'],
            'round' => $standingsList['round'],
            'standing' => $standingsList['DriverStandings'][0] ?? null,
        ];
    }

    /**
     * Get current season year
     */
    public function getCurrentSeason(): int
    {
        return now()->year;
    }
}
