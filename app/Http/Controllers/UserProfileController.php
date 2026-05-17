<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function show(User|int $user)
    {
        if (!$user instanceof User) {
            $user = User::findOrFail($user);
        }

        $user->loadCount([
            'followers',
            'following',
        ]);

        $recipes = $user->recipes()
            ->where('visibility', 1)
            ->orderBy('id', 'desc')
            ->get();

        /*
         * De momento, si todavía no tienes sistema de likes real,
         * dejamos el total de likes a 0.
         */
        $totalLikes = 0;

        return view('users.show', compact('user', 'recipes', 'totalLikes'));
    }
}
