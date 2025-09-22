<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Services\JolpicaF1Service;

class ConstructorController extends Controller
{
    protected $f1;

    public function __construct(JolpicaF1Service $f1)
    {
        $this->f1 = $f1;
    }

    public function constructorsStandings($season)
    {
        $standings = DB::table('standings')
            ->join('constructors', 'standings.constructor_id', '=', 'constructors.id')
            ->select(
                'constructors.name as constructor_name',
                'constructors.nationality as constructor_nationality',
                DB::raw('SUM(standings.points) as points'),
                DB::raw('SUM(standings.wins) as wins')
            )
            ->where('standings.season', $season)
            ->groupBy('constructors.name', 'constructors.nationality')
            ->orderByDesc('points')
            ->get();

        return view('standings.constructors', compact('standings', 'season'));
    }
}
?>