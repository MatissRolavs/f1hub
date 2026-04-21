<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FakeUsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'MaxFanatic',    'email' => 'maxfanatic@f1hub.test'],
            ['name' => 'Leclerc16',     'email' => 'leclerc16@f1hub.test'],
            ['name' => 'NorrisArmy',    'email' => 'norrisarmy@f1hub.test'],
            ['name' => 'SilverArrow',   'email' => 'silverarrow@f1hub.test'],
            ['name' => 'PitlaneHero',   'email' => 'pitlanehero@f1hub.test'],
            ['name' => 'TifosiFuria',   'email' => 'tifosifuria@f1hub.test'],
            ['name' => 'DRSZone',       'email' => 'drszone@f1hub.test'],
            ['name' => 'GrandPrixGuru', 'email' => 'grandprixguru@f1hub.test'],
            ['name' => 'PapamiasF1',    'email' => 'papamiasf1@f1hub.test'],
            ['name' => 'SlipstreamKing','email' => 'slipstreamking@f1hub.test'],
        ];

        foreach ($users as $data) {
            User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['name'],
                    'password' => Hash::make('password'),
                    'role'     => 'user',
                ]
            );
        }
    }
}
