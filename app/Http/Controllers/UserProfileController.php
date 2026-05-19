<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
            ->withCount('likes')
            ->orderBy('id', 'desc')
            ->get();

        $totalLikes = $recipes->sum('likes_count');
        $isFollowing = Auth::check()
            && Auth::id() !== $user->id
            && Auth::user()->following()->where('following_id', $user->id)->exists();
        $isBlocked = Auth::check()
            && Auth::id() !== $user->id
            && Auth::user()->hasBlocked($user);

        return view('users.show', compact('user', 'recipes', 'totalLikes', 'isFollowing', 'isBlocked'));
    }
}
