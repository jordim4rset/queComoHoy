<?php

namespace Tests\Feature;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_recipe_search_page_renders_filters_and_results(): void
    {
        $user = User::factory()->create(['username' => 'chef_arroz']);
        $recipe = $this->createRecipe($user, 'Arroz con pollo');

        $response = $this->get(route('recetas.search'));

        $response
            ->assertOk()
            ->assertSee('Buscar recetas')
            ->assertSee('Usuario')
            ->assertSee('Ingrediente')
            ->assertSee($recipe->name);
    }

    public function test_recipe_search_can_filter_by_user_ingredient_and_time(): void
    {
        $jordi = User::factory()->create(['username' => 'jordi_cocina']);
        $alex = User::factory()->create(['username' => 'alex_cocina']);
        $tomate = Ingredient::create([
            'name' => 'Tomate',
            'normalized_name' => 'tomate',
            'icon' => 'img/ingredientes/cover/default.png',
            'category' => 'Verdura',
        ]);

        $matchingRecipe = $this->createRecipe($jordi, 'Ensalada de tomate', 12);
        $matchingRecipe->ingredients()->attach($tomate->id);

        $otherRecipe = $this->createRecipe($alex, 'Guiso largo', 90);

        $response = $this->get(route('recetas.search', [
            'user' => 'jordi',
            'ingredient' => 'tomate',
            'max_time' => 20,
        ]));

        $response
            ->assertOk()
            ->assertSee($matchingRecipe->name)
            ->assertDontSee($otherRecipe->name);
    }

    public function test_recipe_search_does_not_show_private_recipes(): void
    {
        $user = User::factory()->create();
        $publicRecipe = $this->createRecipe($user, 'Publica rica');
        $privateRecipe = $this->createRecipe($user, 'Privada secreta', 15, false);

        $response = $this->get(route('recetas.search'));

        $response
            ->assertOk()
            ->assertSee($publicRecipe->name)
            ->assertDontSee($privateRecipe->name);
    }

    public function test_aside_links_to_recipe_search_page(): void
    {
        $response = $this->get(route('index'));

        $response
            ->assertOk()
            ->assertSee(route('recetas.search'), false)
            ->assertSee('Buscar recetas');
    }

    private function createRecipe(User $user, string $name, int $time = 25, bool $visible = true): Recipe
    {
        $recipe = new Recipe();
        $recipe->name = $name;
        $recipe->description = 'Receta para probar busqueda';
        $recipe->time = $time;
        $recipe->tags = 'test';
        $recipe->visibility = $visible ? 1 : 0;
        $recipe->image = 'img/recipes/cover/test.jpg';
        $recipe->user_id = $user->id;
        $recipe->save();

        return $recipe;
    }
}
