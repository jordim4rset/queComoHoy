<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    // Seguir
    public function follow($id)
    {
        $user = Auth::user();

        if ($user->id == $id) {
            return back()->with('error', 'No puedes seguirte a ti mismo');
        }

        if ($user->following()->where('following_id', $id)->exists()) {
            return back()->with('error', 'Ya lo sigues');
        }

        $user->following()->attach($id);

        return back();
    }

    // Dejar de seguir
    public function unfollow($id)
    {
        $user = Auth::user();
        $user->following()->detach($id);

        return back();
    }

    // Vista seguidores
    public function followersView($id)
    {
        $user = User::findOrFail($id);
        $followers = $user->followers;

        return view('follows.followers', compact('user', 'followers'));
    }

    // Vista siguiendo
    public function followingView($id)
    {
        $user = User::findOrFail($id);
        $following = $user->following;

        return view('follows.following', compact('user', 'following'));
    }
}