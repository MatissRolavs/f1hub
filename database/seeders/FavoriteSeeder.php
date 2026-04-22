<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        // Each fake user gets a personality-appropriate fav driver + team
        $assignments = [
            'MaxFanatic'    => ['driver' => 'max_verstappen', 'constructor' => 'red_bull'],
            'Leclerc16'     => ['driver' => 'leclerc',        'constructor' => 'ferrari'],
            'NorrisArmy'    => ['driver' => 'norris',         'constructor' => 'mclaren'],
            'SilverArrow'   => ['driver' => 'russell',        'constructor' => 'mercedes'],
            'PitlaneHero'   => ['driver' => 'piastri',        'constructor' => 'mclaren'],
            'TifosiFuria'   => ['driver' => 'sainz',          'constructor' => 'ferrari'],
            'DRSZone'       => ['driver' => 'hamilton',       'constructor' => 'mercedes'],
            'GrandPrixGuru' => ['driver' => 'alonso',         'constructor' => 'aston_martin'],
            'PapamiasF1'    => ['driver' => 'perez',          'constructor' => 'red_bull'],
            'SlipstreamKing'=> ['driver' => 'albon',          'constructor' => 'williams'],
        ];

        // Pre-load driver and constructor IDs keyed by their string IDs
        $driverIds      = \App\Models\Driver::whereIn('driver_id', array_column($assignments, 'driver'))
                            ->pluck('id', 'driver_id');
        $constructorIds = \App\Models\Constructor::whereIn('constructor_id', array_column($assignments, 'constructor'))
                            ->pluck('id', 'constructor_id');

        $updated = 0;
        foreach ($assignments as $name => $picks) {
            $user = User::where('name', $name)->first();
            if (!$user) continue;

            $user->update([
                'favorite_driver_id'      => $driverIds[$picks['driver']]      ?? null,
                'favorite_constructor_id' => $constructorIds[$picks['constructor']] ?? null,
            ]);
            $updated++;
        }

        $this->command->info("Assigned favourite drivers and teams to {$updated} users.");
    }
}
