<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Race;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ForumSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereIn('email', [
            'maxfanatic@f1hub.test', 'leclerc16@f1hub.test', 'norrisarmy@f1hub.test',
            'silverarrow@f1hub.test', 'pitlanehero@f1hub.test', 'tifosifuria@f1hub.test',
            'drszone@f1hub.test', 'grandprixguru@f1hub.test', 'papamiasf1@f1hub.test',
            'slipstreamking@f1hub.test',
        ])->get()->keyBy('name');

        if ($users->isEmpty()) {
            $this->command->warn('No fake users found — run FakeUsersSeeder first.');
            return;
        }

        $races = Race::where('season', 2026)
            ->where('date', '<', now())
            ->orderByDesc('date')
            ->take(8)
            ->get();

        if ($races->isEmpty()) {
            $this->command->warn('No past 2025 races found in DB.');
            return;
        }

        $postTemplates = [
            // Abu Dhabi
            24 => [
                ['title' => 'What a way to end the season 🏁', 'body' => 'Incredible race to wrap up 2025. The battle through the midfield in the final stint was some of the best racing we\'ve seen all year. Abu Dhabi usually produces processional races but this was something else entirely.'],
                ['title' => 'Tyre strategy analysis — who got it right?', 'body' => 'Really interesting strategy calls today. The team that split their two cars onto different compounds early on clearly had inside info on the degradation rates. Did anyone else notice the gap between the soft and medium users closing faster than expected?'],
                ['title' => 'Season review — who impressed you most in 2025?', 'body' => 'Now that the season is done, I want to hear your takes. Forget the championship battle — which driver individually impressed you most this year? For me it has to be someone who punched above the weight of their machinery.'],
            ],
            // Qatar
            23 => [
                ['title' => 'Sprint weekend format — love it or hate it?', 'body' => 'Another sprint weekend and honestly I\'m warming up to the format. The extra strategic layer with trying to save tyres for the main race while still pushing in the sprint makes for some great mind games from the pit walls.'],
                ['title' => 'Penalty discussion — was it fair?', 'body' => 'The track limits calls today were absolutely brutal. Three drivers got penalties in the closing laps which completely reshuffled the top five. The inconsistency from the stewards across the weekend has been embarrassing.'],
            ],
            // Las Vegas
            22 => [
                ['title' => 'Las Vegas under the lights was 🔥', 'body' => 'Say what you want about street circuits but the visuals tonight were stunning. Cars sliding through the casino section at 200mph with all the neon lights reflecting off the bodywork. This race has really found its identity.'],
                ['title' => 'Did the safety car ruin the race or save it?', 'body' => 'Huge debate in my house watching this. The safety car at lap 38 bunched everyone up right when we were getting a comfortable top 3. Some argue it spiced things up — I say it robbed us of a clean fight to the flag.'],
                ['title' => 'P1 strategy was genius', 'body' => 'The call to pit under the VSC when everyone else stayed out was absolutely clinical. That\'s the kind of decision that separates good teams from great ones. The timing with the track position they gained was just perfect.'],
            ],
            // Sao Paulo
            21 => [
                ['title' => 'Interlagos is still the best race on the calendar', 'body' => 'Every. Single. Year. Interlagos delivers. The atmosphere, the elevation changes, the weather unpredictability — there\'s no circuit on the calendar that produces drama as consistently as this one. Today was no exception.'],
                ['title' => 'Rain strategy chaos — full breakdown', 'body' => 'Okay let me break this down because the timing of the rain completely changed the race. First stint on inters, then a brief dry window where two teams gambled on slicks early, then the second rain shower caught everyone out. Chaotic and brilliant.'],
            ],
            // Mexico
            20 => [
                ['title' => 'High altitude = insane overcut opportunities', 'body' => 'The thin air at Autodromo Hermanos Rodriguez always makes tyre management weird and today was a perfect example. The undercuts that usually work here completely failed and we saw three successful overcutters in the top five. Fascinating.'],
                ['title' => 'Best crowd atmosphere of the season?', 'body' => 'The Mexican fans are absolutely unreal. The sea of orange and green in the grandstands, the noise levels — genuinely one of the best atmospheres in sport. Even the drivers were commenting on it in the press conference.'],
            ],
            // COTA
            19 => [
                ['title' => 'COTA bumps cost two teams dearly today', 'body' => 'The kerbs at Turn 9 claimed another victim today. Two retirements from teams that were running strong strategies — both traced back to floor damage from the notorious Austin bumps. FIA really needs to address this permanently.'],
                ['title' => 'Is Austin becoming a classic F1 venue?', 'body' => 'COTA has been on the calendar since 2012 and I think it\'s finally earning classic status. The varied terrain, the elevation changes on the back straight, the amphitheatre section — it rewards proper racing cars and brave drivers.'],
            ],
            // Singapore
            18 => [
                ['title' => 'Marina Bay at night is unlike anything else', 'body' => 'The Singapore street circuit under the lights remains the most visually striking race on the calendar. The heat, the walls, the casino backdrop — it\'s a completely different world from the normal F1 environment.'],
                ['title' => 'Qualifying matters more here than anywhere — discuss', 'body' => 'Overtaking at Marina Bay is basically impossible unless you have a significant pace advantage. Today\'s race was basically decided in qualifying. Grid position was everything. The driver who starts P1 almost always wins here.'],
            ],
            // Azerbaijan
            17 => [
                ['title' => 'Baku wall claims again 😬', 'body' => 'The castle section in Baku has claimed another championship contender. That millimetre-perfect commitment through the walls separates the brave from the foolhardy. Some drivers just have an instinct for street circuits that others will never develop.'],
                ['title' => 'Longest straight in F1 — power unit rankings today', 'body' => 'The Baku straight is the ultimate power unit diagnostic. Top speeds today told an interesting story about where each manufacturer stands heading into the final third of the season. The gaps were actually smaller than I expected.'],
            ],
        ];

        $genericPosts = [
            ['title' => 'Race reaction — what a weekend', 'body' => 'Still processing everything that happened today. The first lap drama set the tone and it never really calmed down from there. Genuinely one of the more entertaining races in recent memory, even without a title decider on the line.'],
            ['title' => 'Tyre management was the story today', 'body' => 'The medium compounds behaved completely differently to what Pirelli predicted on Thursday. Several teams got caught out and had to pit early, which completely scrambled the strategies. In the end the teams who stayed calm made the most.'],
            ['title' => 'DRS detection point ruining racing here?', 'body' => 'The detection zone placement at this circuit basically guarantees that anyone who gets within a second at the start of the main straight will overtake on the back. It removes the driver skill element from what should be a passing opportunity earned under braking.'],
            ['title' => 'Podium celebration was hilarious 😂', 'body' => 'Did anyone catch the podium celebrations? The champagne fight went completely off script and the look on the third place driver\'s face when he got absolutely drenched was priceless. These moments remind you that underneath it all they\'re just having fun.'],
            ['title' => 'My predictions were rubbish again 💀', 'body' => 'Zero from three on the prediction game this weekend. I had Verstappen, Leclerc and Norris on the podium and somehow none of them ended up there. The midfield chaos in lap one completely invalidated every prediction I\'d carefully made.'],
            ['title' => 'Who drives better in the rain — your rankings', 'body' => 'The damp conditions in qualifying today brought out some unexpected heroes. Posting your wet weather driver rankings — I want to see if the community agrees with the received wisdom or if we\'ve been overrating some names.'],
        ];

        $commentPool = [
            'Completely agree, the timing of that strategy call was immaculate.',
            'Disagree — the stewards made the right call in my opinion, even if it was controversial.',
            'This is exactly what I was thinking watching it live. The pace difference was so obvious.',
            'Great breakdown. I hadn\'t considered the tyre temp angle, that changes my read on it.',
            'The team radio from this race is going to be incredible when it gets released.',
            'Real question — does this change the championship picture going into the next round?',
            'I said it before the race and I\'ll say it again: track position is everything here.',
            'The engineers on that call deserve massive credit. Pure cold calculation under pressure.',
            'Hot take but I think this race showed that car development is more decisive than driver skill right now.',
            'Watching this live vs watching the highlights is a totally different experience. Live felt chaotic.',
            'That recovery drive from the back of the grid was absolutely mental. Passed 14 cars in 20 laps.',
            'Pirelli called it wrong again. The deg rates were nothing like the predictions.',
            'First lap incidents like that are why I always watch the start from the helicopter cam.',
            'People sleeping on what that result means for the constructors championship.',
            'The safety car timing was either genius or completely coincidental. We\'ll never know which.',
            'Give that strategist a raise. Immediately.',
            'I\'ve been watching F1 for 20 years and this is one of the more bizarre results I can remember.',
            'The gap between the top two teams and everyone else is just depressing at this point.',
            'Absolutely buzzing after that result. Didn\'t expect that at all going into race day.',
            'Anyone else feel like the broadcast totally missed the best battles today?',
        ];

        $userList = $users->values();
        $now = Carbon::now();

        foreach ($races as $race) {
            $templates = $postTemplates[$race->round] ?? $genericPosts;
            $raceDate  = Carbon::parse($race->date);

            // Pick 2–4 posts per race
            $postCount = min(count($templates), rand(2, 4));
            $selectedPosts = array_slice($templates, 0, $postCount);

            foreach ($selectedPosts as $idx => $template) {
                $author    = $userList->random();
                $postTime  = $raceDate->copy()->addHours(rand(2, 18))->addMinutes(rand(0, 59));

                $postId = DB::table('forum_posts')->insertGetId([
                    'race_id'    => $race->id,
                    'user_id'    => $author->id,
                    'title'      => $template['title'],
                    'body'       => $template['body'],
                    'created_at' => $postTime,
                    'updated_at' => $postTime,
                ]);

                // 3–7 comments per post
                $commentCount = rand(3, 7);
                $shuffledComments = collect($commentPool)->shuffle()->take($commentCount);
                $usedLikers = [];

                foreach ($shuffledComments as $ci => $commentBody) {
                    $commenter    = $userList->random();
                    $commentTime  = $postTime->copy()->addMinutes(rand(5, 60) * ($ci + 1));

                    DB::table('comments')->insert([
                        'forum_post_id' => $postId,
                        'user_id'       => $commenter->id,
                        'body'          => $commentBody,
                        'created_at'    => $commentTime,
                        'updated_at'    => $commentTime,
                    ]);
                }

                // 2–6 likes per post (unique per user)
                $likerCount = rand(2, 6);
                $likers = $userList->shuffle()->take($likerCount)->unique('id');
                foreach ($likers as $liker) {
                    DB::table('likes')->insertOrIgnore([
                        'forum_post_id' => $postId,
                        'user_id'       => $liker->id,
                        'created_at'    => $postTime->copy()->addMinutes(rand(1, 120)),
                        'updated_at'    => $postTime->copy()->addMinutes(rand(1, 120)),
                    ]);
                }
            }
        }

        $this->command->info('Forum posts, comments and likes seeded for ' . $races->count() . ' races.');
    }
}
