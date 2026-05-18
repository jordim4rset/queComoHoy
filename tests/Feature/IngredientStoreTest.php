<?php

namespace Tests\Feature;

use App\Models\Ingredient;
use App\Services\IngredientAiValidator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IngredientStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_validates_and_corrects_an_ingredient_when_created_from_the_form(): void
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

        $response = $this->post(route('ingredientes.store'), [
            'name' => 'tomatte',
            'category' => 'Fruta',
        ]);

        $response->assertRedirect(route('ingredientes.index'));

        $this->assertDatabaseHas('ingredients', [
            'name' => 'Tomate',
            'normalized_name' => 'tomate',
            'category' => 'Verdura',
        ]);
    }

    public function test_it_rejects_non_food_text_when_created_from_the_form(): void
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
            ->from(route('ingredientes.create'))
            ->post(route('ingredientes.store'), [
                'name' => 'martillo',
                'category' => 'Verdura',
            ]);

        $response
            ->assertRedirect(route('ingredientes.create'))
            ->assertSessionHasErrors('name');

        $this->assertDatabaseMissing('ingredients', [
            'name' => 'martillo',
        ]);
    }

    public function test_it_rejects_duplicates_after_api_correction(): void
    {
        Ingredient::create([
            'name' => 'Tomate',
            'normalized_name' => 'tomate',
            'icon' => 'img/ingredientes/cover/default.png',
            'category' => 'Verdura',
        ]);

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
            ->from(route('ingredientes.create'))
            ->post(route('ingredientes.store'), [
                'name' => 'tomatte',
                'category' => 'Verdura',
            ]);

        $response
            ->assertRedirect(route('ingredientes.create'))
            ->assertSessionHasErrors('name');

        $this->assertSame(1, Ingredient::where('normalized_name', 'tomate')->count());
    }
}
