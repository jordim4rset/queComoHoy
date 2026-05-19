<?php

namespace Tests\Feature;

use App\Models\Ingredient;
use App\Models\User;
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
            'category' => 'Verdura',
        ]);

        $response = $this->get(route('ingredientes.index'));

        $response
            ->assertOk()
            ->assertSee('Editar')
            ->assertSee('Eliminar');
    }

    public function test_ingredients_views_do_not_show_icon_fields(): void
    {
        Ingredient::create([
            'name' => 'Tomate',
            'normalized_name' => 'tomate',
            'category' => 'Verdura',
        ]);

        $this->get(route('ingredientes.index'))
            ->assertOk()
            ->assertDontSee('Icono')
            ->assertDontSee('name="icon"', false);

        $this->get(route('ingredientes.create'))
            ->assertOk()
            ->assertDontSee('Icono')
            ->assertDontSee('name="icon"', false);
    }

    public function test_ingredient_search_does_not_return_icon_data(): void
    {
        Ingredient::create([
            'name' => 'Tomate',
            'normalized_name' => 'tomate',
            'category' => 'Verdura',
        ]);

        $response = $this
            ->actingAs(User::factory()->create())
            ->getJson(route('ingredientes.searchForRecipe', ['q' => 'tom']));

        $response
            ->assertOk()
            ->assertJsonMissingPath('0.icon');
    }

    public function test_ingredient_can_be_deleted(): void
    {
        $ingredient = Ingredient::create([
            'name' => 'Tomate',
            'normalized_name' => 'tomate',
            'category' => 'Verdura',
        ]);

        $response = $this->delete(route('ingredientes.destroy', ['ingrediente' => $ingredient->id]));

        $response->assertRedirect(route('ingredientes.index'));

        $this->assertDatabaseMissing('ingredients', [
            'id' => $ingredient->id,
        ]);
    }
}
