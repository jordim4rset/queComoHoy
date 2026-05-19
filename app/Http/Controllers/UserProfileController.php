<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    private const RECIPES_PER_PAGE = 5;

    public function show(Request $request, User|int $user)
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
            ->paginate(self::RECIPES_PER_PAGE);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('users.partials.recipe-cards', compact('recipes'))->render(),
                'next_page_url' => $recipes->nextPageUrl(),
            ]);
        }

        $recipeStats = $user->recipes()
            ->where('visibility', 1)
            ->withCount('likes')
            ->get();
        $totalRecipes = $recipeStats->count();
        $totalLikes = $recipeStats->sum('likes_count');
        $isFollowing = Auth::check()
            && Auth::id() !== $user->id
            && Auth::user()->following()->where('following_id', $user->id)->exists();
        $isBlocked = Auth::check()
            && Auth::id() !== $user->id
            && Auth::user()->hasBlocked($user);

        return view('users.show', compact('user', 'recipes', 'totalRecipes', 'totalLikes', 'isFollowing', 'isBlocked'));
    }
}
