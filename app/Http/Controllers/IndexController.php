<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $recipes = Recipe::with(['user', 'likes'])
            ->where('visibility', 1)
            ->orderBy('id', 'desc')
            ->get();

        $followingUserIds = $request->user()
            ? $request->user()->following()->pluck('users.id')->all()
            : [];

        $suggestions = User::when($request->user(), function ($query) use ($request) {
                $query->where('id', '!=', $request->user()->id);
            })
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('index', compact('recipes', 'suggestions', 'followingUserIds'));
    }
}
