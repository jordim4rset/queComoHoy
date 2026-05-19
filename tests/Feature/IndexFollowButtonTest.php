<?php

namespace Tests\Feature;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexFollowButtonTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_does_not_see_follow_button_on_index(): void
    {
        $author = User::factory()->create();
        $this->createRecipeFor($author);

        $this->get(route('index'))
            ->assertOk()
            ->assertDontSeeText('Seguir')
            ->assertDontSeeText('Dejar de seguir');
    }

    public function test_logged_user_sees_follow_button_for_recipe_author_not_followed(): void
    {
        $viewer = User::factory()->create();
        $author = User::factory()->create();
        $this->createRecipeFor($author);

        $this->actingAs($viewer)
            ->get(route('index'))
            ->assertOk()
            ->assertSeeText('Seguir')
            ->assertDontSeeText('Dejar de seguir');
    }

    public function test_logged_user_does_not_see_follow_button_for_recipe_author_already_followed(): void
    {
        $viewer = User::factory()->create();
        $author = User::factory()->create();
        $this->createRecipeFor($author);

        $viewer->following()->attach($author->id);

        $this->actingAs($viewer)
            ->get(route('index'))
            ->assertOk()
            ->assertDontSeeText('Seguir')
            ->assertDontSeeText('Dejar de seguir');
    }

    public function test_following_feed_only_shows_recipes_from_followed_users(): void
    {
        $viewer = User::factory()->create();
        $followedAuthor = User::factory()->create();
        $otherAuthor = User::factory()->create();

        $followedRecipe = $this->createRecipeFor($followedAuthor, 'Receta seguida');
        $otherRecipe = $this->createRecipeFor($otherAuthor, 'Receta fuera');

        $viewer->following()->attach($followedAuthor->id);

        $this->actingAs($viewer)
            ->get(route('following.feed'))
            ->assertOk()
            ->assertSeeText($followedRecipe->name)
            ->assertDontSeeText($otherRecipe->name)
            ->assertDontSeeText('Sugerencias para ti');
    }

    public function test_aside_links_to_following_feed_and_not_followers_page(): void
    {
        $viewer = User::factory()->create();

        $this->actingAs($viewer)
            ->get(route('index'))
            ->assertOk()
            ->assertSee(route('following.feed'), false)
            ->assertDontSee(route('user.followers', $viewer->id), false);
    }

    private function createRecipeFor(User $user, string $name = 'Tortilla'): Recipe
    {
        $recipe = new Recipe();
        $recipe->name = $name;
        $recipe->description = 'Receta sencilla para probar el feed';
        $recipe->time = 20;
        $recipe->tags = 'huevos';
        $recipe->visibility = 1;
        $recipe->image = 'img/recipes/tortilla.jpg';
        $recipe->user_id = $user->id;
        $recipe->save();

        return $recipe;
    }
}
