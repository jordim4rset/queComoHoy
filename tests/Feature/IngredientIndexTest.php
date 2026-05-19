<?php

namespace Tests\Feature;

use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IngredientIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_ingredients_index_shows_delete_button(): void
    {
        Ingredient::create([
            'name' => 'Tomate',
            'normalized_name' => 'tomate',
            'icon' => 'img/ingredientes/cover/default.png',
            'category' => 'Verdura',
        ]);

        $response = $this->get(route('ingredientes.index'));

        $response
            ->assertOk()
            ->assertSee('Editar')
            ->assertSee('Eliminar');
    }

    public function test_ingredient_can_be_deleted(): void
    {
        $ingredient = Ingredient::create([
            'name' => 'Tomate',
            'normalized_name' => 'tomate',
            'icon' => 'img/ingredientes/cover/default.png',
            'category' => 'Verdura',
        ]);

        $response = $this->delete(route('ingredientes.destroy', ['ingrediente' => $ingredient->id]));

        $response->assertRedirect(route('ingredientes.index'));

        $this->assertDatabaseMissing('ingredients', [
            'id' => $ingredient->id,
        ]);
    }
}
