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

    public function test_it_finds_an_existing_ingredient_after_a_common_typo_correction(): void
    {
        $ingredient = Ingredient::create([
            'name' => 'Boqueron',
            'normalized_name' => 'boqueron',
            'icon' => 'img/ingredientes/cover/default.png',
            'category' => 'Pescado',
        ]);

        $this->app->instance(IngredientAiValidator::class, new class extends IngredientAiValidator {
            public function validate(string $ingredientName): array
            {
                throw new \RuntimeException('No deberia llamar a la API si ya existe el ingrediente corregido.');
            }
        });

        $response = $this
            ->actingAs(User::factory()->create())
            ->postJson(route('ingredientes.storeFromRecipe'), [
                'name' => 'voqueron',
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('ingredient.id', $ingredient->id);

        $this->assertSame(1, Ingredient::where('normalized_name', 'boqueron')->count());
    }

    public function test_search_results_include_normalized_names_for_existing_ingredient_selection(): void
    {
        Ingredient::create([
            'name' => 'Boqueron',
            'normalized_name' => 'boqueron',
            'icon' => 'img/ingredientes/cover/default.png',
            'category' => 'Pescado',
        ]);

        $response = $this
            ->actingAs(User::factory()->create())
            ->getJson(route('ingredientes.searchForRecipe', ['q' => 'boq']));

        $response
            ->assertOk()
            ->assertJsonPath('0.name', 'Boqueron')
            ->assertJsonPath('0.normalized_name', 'boqueron');
    }
}
