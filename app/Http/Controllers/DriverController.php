<?php

namespace App\Http\Controllers;

use App\Services\OpenF1Service;

class DriverController extends Controller
{
    protected $openF1;

    public function __construct(OpenF1Service $openF1)
    {
        $this->openF1 = $openF1;
    }

    public function index()
    {   
        $drivers = collect(
            $this->openF1->getDrivers([
                'session_key' => 'latest'
            ])
        )
        ->unique('driver_number') // keep only one per driver
        ->values(); // reset array keys
        return view('drivers.index', compact('drivers'));
    }
    

}

?>