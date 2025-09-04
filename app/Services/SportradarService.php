<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;


class SportradarService
{
    protected $baseUrl = 'https://api.sportradar.com/formula1/production/v2/en/';
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('SPORTRADAR_API_KEY');
    }

    public function getCompetitorProfile($competitorId)
    {
        $url = $this->baseUrl . "competitor/{$competitorId}/profile";

        $response = Http::withHeaders([
            'accept' => 'application/json',
        ])->get($url, [
            'api_key' => $this->apiKey
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    public function getCurrentSeasonId()
    {
        $url = $this->baseUrl . 'seasons.json';

        $response = Http::get($url, [
            'api_key' => $this->apiKey
        ]);

        if ($response->failed()) {
            return null;
        }

        $seasons = $response->json()['seasons'] ?? [];

        // Find the one marked as "current"
        $current = collect($seasons)->firstWhere('current', true);

        return $current['id'] ?? null;
    }

    /**
     * Get all drivers for the current season
     */
    /**
 * Get all events for a season (schedule).
 */
protected function getSeasonEvents(string $seasonId): array
{
    $url = $this->baseUrl . "seasons/{$seasonId}/schedule.json";

    $response = Http::get($url, [
        'api_key' => $this->apiKey
    ]);

    if ($response->failed()) {
        return [];
    }

    return $response->json()['events'] ?? [];
}

/**
 * Fetch all driver competitors for a single event.
 */
protected function getEventDrivers(string $eventId): array
{
    $url = $this->baseUrl . "events/{$eventId}/summary.json";

    $response = Http::get($url, [
        'api_key' => $this->apiKey
    ]);

    if ($response->failed()) {
        return [];
    }

    $competitors = $response->json()['competitors'] ?? [];

    // Filter only drivers (be tolerant to casing)
    return collect($competitors)
        ->filter(function ($c) {
            $type = strtolower($c['type'] ?? '');
            return $type === 'driver';
        })
        ->values()
        ->all();
}

/**
 * Optimized: get all drivers using only the first event and the latest finished event.
 * Falls back gracefully if some data is missing.
 */
public function getCurrentSeasonDrivers(): array
{
    // Cache for 6 hours to save credits
    return Cache::remember('f1:current_drivers', 60 * 60 * 6, function () {
        $seasonId = $this->getCurrentSeasonId();
        if (!$seasonId) {
            return [];
        }

        $events = $this->getSeasonEvents($seasonId);
        if (empty($events)) {
            return [];
        }

        // First event (assumes schedule is chronological; if not, we sort by 'scheduled')
        $eventsCollection = collect($events);

        $eventsCollection = $eventsCollection->sortBy(function ($e) {
            // Try to sort by ISO 'scheduled' timestamp; fallback to original order
            return $e['scheduled'] ?? '';
        })->values();

        $firstEvent = $eventsCollection->first();

        // Latest finished/closed event; fallback to the last on the list
        $statusFinished = ['closed', 'finished', 'ended', 'complete'];
        $latestFinished = $eventsCollection->reverse()->first(function ($e) use ($statusFinished) {
            $status = strtolower($e['status'] ?? '');
            return in_array($status, $statusFinished, true);
        }) ?? $eventsCollection->last();

        // Unique event IDs to call (1 or 2 calls)
        $eventIds = collect([$firstEvent['id'] ?? null, $latestFinished['id'] ?? null])
            ->filter()                 // remove nulls
            ->unique()
            ->values()
            ->all();

        if (empty($eventIds)) {
            return [];
        }

        // Collect drivers from selected events
        $drivers = collect();
        foreach ($eventIds as $eventId) {
            $drivers = $drivers->merge($this->getEventDrivers($eventId));
        }

        // Deduplicate by driver id
        return $drivers->unique('id')->values()->all();
    });
}

    
}
