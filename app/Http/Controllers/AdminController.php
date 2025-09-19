<?php

namespace App\Http\Controllers;
use App\Models\Race;
use Illuminate\Http\Request;

class AdminController extends Controller{

    public function panel()
    {
        return view('admin.panel');
    }
    public function data(){
        return
        view('admin.data');
    }
    public function index()
    {
        $races = Race::orderBy('date')->get();
        return view('admin.races.index', compact('races'));
    }

    

public function edit(Race $race)
{
    return view('admin.races.edit', compact('race'));
}

public function update(Request $request, Race $race)
{
    $validated = $request->validate([
        'track_image'   => 'nullable|url',
        'track_length'  => 'nullable|string|max:50',
        'turns'         => 'nullable|integer|min:0',
        'description'   => 'nullable|string',
        'lap_record'    => 'nullable|string|max:100',
    ]);

    $race->update($validated);

    return redirect()->route('admin.races.index')->with('success', 'Race details updated successfully.');
}
}
?>