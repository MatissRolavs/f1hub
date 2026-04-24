<?php

namespace App\Http\Controllers;
use App\Models\Race;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

// ── User Management ───────────────────────────────────────────

public function users(Request $request)
{
    abort_unless(Auth::user()->role === 'admin', 403);

    $search = $request->get('search');
    $role   = $request->get('role');

    $users = User::withCount(['favoriteConstructor' => fn($q) => $q])
        ->with(['favoriteConstructor', 'favoriteDriver'])
        ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%"))
        ->when($role, fn($q) => $q->where('role', $role))
        ->orderBy('created_at', 'desc')
        ->paginate(15)
        ->withQueryString();

    return view('admin.users.index', compact('users', 'search', 'role'));
}

public function updateUserRole(Request $request, User $user)
{
    abort_unless(Auth::user()->role === 'admin', 403);
    abort_if($user->id === Auth::id(), 403, 'Cannot change your own role.');

    $request->validate(['role' => 'required|in:user,admin']);
    $user->update(['role' => $request->role]);

    return back()->with('success', "Role updated to \"{$request->role}\" for {$user->name}.");
}

public function resetUserPassword(Request $request, User $user)
{
    abort_unless(Auth::user()->role === 'admin', 403);

    $request->validate(['password' => 'required|string|min:8|confirmed']);
    $user->update(['password' => Hash::make($request->password)]);

    return back()->with('success', "Password reset for {$user->name}.");
}

public function deleteUser(User $user)
{
    abort_unless(Auth::user()->role === 'admin', 403);
    abort_if($user->id === Auth::id(), 403, 'Cannot delete your own account.');

    $name = $user->name;
    $user->delete();

    return back()->with('success', "User \"{$name}\" has been deleted.");
}
}
?>