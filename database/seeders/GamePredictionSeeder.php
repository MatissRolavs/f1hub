<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Race;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GamePredictionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereIn('email', [
            'maxfanatic@f1hub.test', 'leclerc16@f1hub.test', 'norrisarmy@f1hub.test',
            'silverarrow@f1hub.test', 'pitlanehero@f1hub.test', 'tifosifuria@f1hub.test',
            'drszone@f1hub.test', 'grandprixguru@f1hub.test', 'papamiasf1@f1hub.test',
            'slipstreamking@f1hub.test',
        ])->get();

        if ($users->isEmpty()) {
            $this->command->warn('No fake users found — run FakeUsersSeeder first.');
            return;
        }

        $races = Race::where('season', 2025)
            ->where('date', '<', now())
            ->orderBy('round')
            ->get();

        if ($races->isEmpty()) {
            $this->command->warn('No past 2025 races found in DB.');
            return;
        }

        // The actual top-3 finishing order per round (driver_id strings)
        // Used both as the "ground truth" for scoring and as the base for generating
        // plausible-but-imperfect predictions
        $actualResults = [
            1  => ['max_verstappen', 'leclerc', 'piastri'],
            2  => ['norris', 'piastri', 'leclerc'],
            3  => ['max_verstappen', 'norris', 'leclerc'],
            4  => ['leclerc', 'norris', 'piastri'],
            5  => ['norris', 'piastri', 'russell'],
            6  => ['max_verstappen', 'norris', 'russell'],
            7  => ['piastri', 'norris', 'leclerc'],
            8  => ['norris', 'max_verstappen', 'piastri'],
            9  => ['piastri', 'norris', 'max_verstappen'],
            10 => ['russell', 'norris', 'piastri'],
            11 => ['norris', 'piastri', 'hamilton'],
            12 => ['max_verstappen', 'piastri', 'norris'],
            13 => ['piastri', 'norris', 'leclerc'],
            14 => ['leclerc', 'sainz', 'norris'],
            15 => ['norris', 'piastri', 'max_verstappen'],
            16 => ['max_verstappen', 'norris', 'piastri'],
            17 => ['piastri', 'norris', 'leclerc'],
            18 => ['norris', 'russell', 'leclerc'],
            19 => ['max_verstappen', 'norris', 'piastri'],
            20 => ['norris', 'piastri', 'leclerc'],
            21 => ['norris', 'max_verstappen', 'leclerc'],
            22 => ['norris', 'piastri', 'leclerc'],
            23 => ['piastri', 'norris', 'max_verstappen'],
            24 => ['norris', 'piastri', 'leclerc'],
        ];

        $allDrivers = [
            'max_verstappen', 'leclerc', 'norris', 'piastri',
            'russell', 'hamilton', 'alonso', 'sainz', 'perez', 'albon',
        ];

        $now = now();
        $seeded = 0;

        foreach ($races as $race) {
            $round = (int) $race->round;
            $actual = $actualResults[$round] ?? array_slice($allDrivers, 0, 3);
            $raceName = "2025 Round {$round}";

            foreach ($users as $user) {
                // Already has a prediction for this race? Skip.
                $exists = DB::table('race_predictions')
                    ->where('user_id', $user->id)
                    ->where('season', '2025')
                    ->where('round', (string) $round)
                    ->exists();

                if ($exists) continue;

                // Build a prediction: randomly mutate the actual top-3
                $predicted = $this->mutatePrediction($actual, $allDrivers);

                DB::table('race_predictions')->insert([
                    'user_id'         => $user->id,
                    'season'          => '2025',
                    'round'           => (string) $round,
                    'predicted_order' => json_encode($predicted),
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ]);

                // Score = number of positions that match exactly
                $score = 0;
                foreach ($predicted as $pos => $driverId) {
                    if (($actual[$pos] ?? null) === $driverId) {
                        $score++;
                    }
                }

                DB::table('game_scores')->updateOrInsert(
                    ['race_name' => $raceName, 'player_name' => $user->name],
                    [
                        'race_id'    => $race->id,
                        'score'      => $score,
                        'total'      => count($actual),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );

                $seeded++;
            }
        }

        $this->command->info("Seeded {$seeded} predictions and scores across {$races->count()} races.");
    }

    private function mutatePrediction(array $actual, array $allDrivers): array
    {
        $predicted = $actual;

        // Randomly swap one or two positions or replace a driver
        $mutations = rand(0, 2);
        for ($i = 0; $i < $mutations; $i++) {
            $action = rand(0, 1);
            if ($action === 0 && count($predicted) >= 2) {
                // Swap two positions
                $a = rand(0, count($predicted) - 1);
                $b = rand(0, count($predicted) - 1);
                [$predicted[$a], $predicted[$b]] = [$predicted[$b], $predicted[$a]];
            } else {
                // Replace one position with a different driver
                $pos = rand(0, count($predicted) - 1);
                $alternatives = array_values(array_diff($allDrivers, $predicted));
                if (!empty($alternatives)) {
                    $predicted[$pos] = $alternatives[array_rand($alternatives)];
                }
            }
        }

        return $predicted;
    }
}
