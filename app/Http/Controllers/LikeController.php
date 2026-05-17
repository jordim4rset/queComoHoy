<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Request $request, $recipeId)
    {
        $user = $request->user();

        $like = Like::where('user_id', $user->id)
                    ->where('recipe_id', $recipeId)
                    ->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            Like::create([
                'user_id' => $user->id,
                'recipe_id' => $recipeId,
            ]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'count' => Like::where('recipe_id', $recipeId)->count()
        ]);
    }
}
