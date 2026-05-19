<?php

namespace Tests\Feature;

use App\Models\Block;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserBlockTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_block_another_user_from_profile(): void
    {
        $viewer = User::factory()->create();
        $blocked = User::factory()->create();

        $this->actingAs($viewer)
            ->post(route('users.block', $blocked))
            ->assertRedirect();

        $this->assertDatabaseHas('blocks', [
            'id_bloqueador' => $viewer->id,
            'id_bloqueado' => $blocked->id,
        ]);
    }

    public function test_blocked_user_recipes_are_hidden_from_for_you_but_profile_remains_visible(): void
    {
        $viewer = User::factory()->create();
        $blocked = User::factory()->create(['username' => 'usuario_bloqueado']);
        $visibleAuthor = User::factory()->create();

        $blockedRecipe = $this->createRecipeFor($blocked, 'Receta bloqueada');
        $visibleRecipe = $this->createRecipeFor($visibleAuthor, 'Receta visible');

        Block::create([
            'id_bloqueador' => $viewer->id,
            'id_bloqueado' => $blocked->id,
        ]);

        $this->actingAs($viewer)
            ->get(route('index'))
            ->assertOk()
            ->assertDontSeeText($blockedRecipe->name)
            ->assertSeeText($visibleRecipe->name);

        $this->actingAs($viewer)
            ->get(route('profile', ['id' => $blocked->id]))
            ->assertOk()
            ->assertSeeText('usuario_bloqueado')
            ->assertSeeText('Desbloquear usuario');
    }

    public function test_user_can_unblock_and_see_recipes_again(): void
    {
        $viewer = User::factory()->create();
        $blocked = User::factory()->create();
        $recipe = $this->createRecipeFor($blocked, 'Receta restaurada');

        Block::create([
            'id_bloqueador' => $viewer->id,
            'id_bloqueado' => $blocked->id,
        ]);

        $this->actingAs($viewer)
            ->delete(route('users.unblock', $blocked))
            ->assertRedirect();

        $this->assertDatabaseMissing('blocks', [
            'id_bloqueador' => $viewer->id,
            'id_bloqueado' => $blocked->id,
        ]);

        $this->actingAs($viewer)
            ->get(route('index'))
            ->assertOk()
            ->assertSeeText($recipe->name);
    }

    public function test_user_cannot_block_themselves(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('users.block', $user))
            ->assertSessionHasErrors('block');

        $this->assertDatabaseMissing('blocks', [
            'id_bloqueador' => $user->id,
            'id_bloqueado' => $user->id,
        ]);
    }

    private function createRecipeFor(User $user, string $name): Recipe
    {
        $recipe = new Recipe();
        $recipe->name = $name;
        $recipe->description = 'Receta para probar bloqueos';
        $recipe->time = 20;
        $recipe->tags = 'test';
        $recipe->visibility = 1;
        $recipe->image = 'img/recipes/test.jpg';
        $recipe->user_id = $user->id;
        $recipe->save();

        return $recipe;
    }
}
