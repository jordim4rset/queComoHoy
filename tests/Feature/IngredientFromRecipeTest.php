<?php

namespace Tests\Feature;

use App\Models\Ingredient;
use App\Models\User;
use App\Services\IngredientAiValidator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IngredientFromRecipeTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_an_ingredient_from_the_recipe_ajax_endpoint(): void
    {
        $this->app->instance(IngredientAiValidator::class, new class extends IngredientAiValidator {
            public function validate(string $ingredientName): array
            {
                return [
                    'is_food' => true,
                    'corrected_name' => 'Tomate',
                    'category' => 'Verdura',
                    'reason' => '',
                ];
            }
        });

        $response = $this
            ->actingAs(User::factory()->create())
            ->postJson(route('ingredientes.storeFromRecipe'), [
                'name' => 'tomatte',
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('ingredient.name', 'Tomate')
            ->assertJsonPath('ingredient.category', 'Verdura');

        $this->assertDatabaseHas('ingredients', [
            'name' => 'Tomate',
            'normalized_name' => 'tomate',
            'category' => 'Verdura',
        ]);
    }

    public function test_it_rejects_text_that_is_not_food(): void
    {
        $this->app->instance(IngredientAiValidator::class, new class extends IngredientAiValidator {
            public function validate(string $ingredientName): array
            {
                return [
                    'is_food' => false,
                    'corrected_name' => '',
                    'category' => 'Verdura',
                    'reason' => 'No es un alimento.',
                ];
            }
        });

        $response = $this
            ->actingAs(User::factory()->create())
            ->postJson(route('ingredientes.storeFromRecipe'), [
                'name' => 'martillo',
            ]);

        $response
            ->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonPath('reason', 'No es un alimento.');

        $this->assertDatabaseMissing('ingredients', [
            'name' => 'martillo',
        ]);
    }

    public function test_it_returns_an_existing_ingredient_without_creating_a_duplicate(): void
    {
        $ingredient = Ingredient::create([
            'name' => 'Tomate',
            'normalized_name' => 'tomate',
            'icon' => 'img/ingredientes/cover/default.png',
            'category' => 'Verdura',
        ]);

        $response = $this
            ->actingAs(User::factory()->create())
            ->postJson(route('ingredientes.storeFromRecipe'), [
                'name' => 'Tomate',
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('ingredient.id', $ingredient->id);

        $this->assertSame(1, Ingredient::where('normalized_name', 'tomate')->count());
    }
}
