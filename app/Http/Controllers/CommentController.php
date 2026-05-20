<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Recipe;

class CommentController extends Controller
{
    public function store(Request $request, Recipe $recipe)
    {
        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $comment = Comment::create([
            'recipe_id' => $recipe->id,
            'user_id' => auth()->id(),
            'content' => $request->content
        ]);

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'content' => $comment->content,
                'username' => $comment->user->username,
                'avatar' => $comment->user->profilePhotoUrl(),
                'created_at' => $comment->created_at->format('d M Y H:i'),
            ]
        ]);
    }

    public function destroy(Comment $comment)
    {
        if (auth()->id() !== $comment->user_id && auth()->user()->id !== $comment->recipe->user_id) {
            abort(403);
        }

        $comment->delete();

        return response()->json(['success' => true]);
    }
}
