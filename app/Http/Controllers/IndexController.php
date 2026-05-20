<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    private const RECIPES_PER_PAGE = 5;

    public function __invoke(Request $request)
    {
        $blockedUserIds = $request->user()
            ? $request->user()->blockedUsers()->pluck('users.id')->all()
            : [];

        $recipes = Recipe::with(['user', 'likes', 'comments.user'])
            ->withCount('comments')
            ->where('visibility', 1)
            ->whereHas('user')
            ->when($blockedUserIds, function ($query, $blockedUserIds) {
                $query->whereNotIn('user_id', $blockedUserIds);
            })
            ->orderBy('id', 'desc')
            ->paginate(self::RECIPES_PER_PAGE);

        $followingUserIds = $request->user()
            ? $request->user()->following()->pluck('users.id')->all()
            : [];

        if ($request->ajax()) {
            return response()->json([
                'html' => view('recipes.partials.feed-posts', compact('recipes', 'followingUserIds'))->render(),
                'next_page_url' => $recipes->nextPageUrl(),
            ]);
        }

        $suggestions = User::when($request->user(), function ($query) use ($request) {
                $query->where('id', '!=', $request->user()->id);
            })
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('index', compact('recipes', 'suggestions', 'followingUserIds'));
    }

    public function following(Request $request)
    {
        $followingUserIds = $request->user()
            ->following()
            ->pluck('users.id')
            ->all();

        $recipes = Recipe::with(['user', 'likes', 'comments.user'])
            ->withCount('comments')
            ->where('visibility', 1)
            ->whereIn('user_id', $followingUserIds)
            ->orderBy('id', 'desc')
            ->paginate(self::RECIPES_PER_PAGE);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('recipes.partials.feed-posts', compact('recipes', 'followingUserIds'))->render(),
                'next_page_url' => $recipes->nextPageUrl(),
            ]);
        }

        $suggestions = collect();
        $showSuggestions = false;
        $emptyMessage = 'Todavía no hay recetas de usuarios a los que sigues.';

        return view('index', compact(
            'recipes',
            'suggestions',
            'followingUserIds',
            'showSuggestions',
            'emptyMessage'
        ));
    }
}
