<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeShowInteractionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_recipe_show_renders_interactive_likes_and_comments(): void
    {
        $viewer = User::factory()->create();
        $author = User::factory()->create();
        $recipe = $this->createRecipeFor($author);

        Like::create([
            'user_id' => $viewer->id,
            'recipe_id' => $recipe->id,
        ]);

        Comment::create([
            'user_id' => $author->id,
            'recipe_id' => $recipe->id,
            'content' => 'Comentario inicial',
        ]);

        $this->actingAs($viewer)
            ->get(route('recetas.show', ['receta' => $recipe]))
            ->assertOk()
            ->assertSee('class="icon-stat like-btn"', false)
            ->assertSee('data-recipe-id="' . $recipe->id . '"', false)
            ->assertSee('data-liked="true"', false)
            ->assertSee('class="stat comment-toggle"', false)
            ->assertSee('comments-count-' . $recipe->id, false)
            ->assertSee('comments-box-' . $recipe->id, false)
            ->assertSeeText('Comentario inicial');
    }

    private function createRecipeFor(User $user): Recipe
    {
        $recipe = new Recipe();
        $recipe->name = 'Tortilla';
        $recipe->description = 'Receta sencilla para probar el detalle';
        $recipe->time = 20;
        $recipe->tags = 'huevos';
        $recipe->visibility = 1;
        $recipe->image = 'img/recipes/tortilla.jpg';
        $recipe->user_id = $user->id;
        $recipe->save();

        return $recipe;
    }
}
