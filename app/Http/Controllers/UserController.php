<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);
        $recipes = $user->recipes()->paginate(6);

        return view('users.profile', compact('user', 'recipes'));
    }
    public function index()
    {
        $currentUserId = auth()->id();

        $users = User::when($currentUserId, function ($query, $currentUserId) {
            $query->where('id', '!=', $currentUserId);
        })->get();

        return view('users.index', compact('users'));
    }

    public function search(Request $request)
{
    $q = $request->input('q');
    $users = User::where('username', 'like', "%{$q}%")
        ->limit(6)
        ->get(['id', 'username']);
    return response()->json($users);
}
}
