<?php

namespace App\Http\Controllers;
use App\Models\Race;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AdminController extends Controller{

    public function panel()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        else
        {
        return view('admin.panel');
        }
    }
    public function data(){
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        else
        {
            return view('admin.data');
        }
    }
    public function index()
    { 
    if (Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized');
    }
    else
    {
        $races = Race::orderBy('date')->get();
        return view('admin.races.index', compact('races'));
    }
    }

    

public function edit(Race $race)
{ 
if (Auth::user()->role !== 'admin') {
    abort(403, 'Unauthorized');
}
else
{
    return view('admin.races.edit', compact('race'));
}
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