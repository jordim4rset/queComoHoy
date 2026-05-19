<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InfiniteScrollPaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_loads_five_recipes_and_ajax_returns_more(): void
    {
        $author = User::factory()->create();

        foreach (range(1, 6) as $number) {
            $this->createRecipeFor($author, "Receta {$number}");
        }

        $this->get(route('index'))
            ->assertOk()
            ->assertSeeText('Receta 6')
            ->assertSeeText('Receta 2')
            ->assertDontSeeText('Receta 1')
            ->assertSee('data-infinite-scroll-trigger', false);

        $response = $this->getJson(route('index', ['page' => 2]), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('next_page_url', null);

        $this->assertStringContainsString('Receta 1', $response->json('html'));
    }

    public function test_profile_loads_five_recipes_and_ajax_returns_more(): void
    {
        $author = User::factory()->create();

        foreach (range(1, 6) as $number) {
            $this->createRecipeFor($author, "Perfil receta {$number}");
        }

        $this->get(route('profile', ['id' => $author->id]))
            ->assertOk()
            ->assertSeeText('Perfil receta 6')
            ->assertSeeText('Perfil receta 2')
            ->assertDontSeeText('Perfil receta 1')
            ->assertSee('data-infinite-scroll-trigger', false);

        $response = $this->getJson(route('profile', ['id' => $author->id, 'page' => 2]), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('next_page_url', null);

        $this->assertStringContainsString('Perfil receta 1', $response->json('html'));
    }

    public function test_events_load_five_items_and_ajax_returns_more(): void
    {
        foreach (range(1, 6) as $number) {
            Event::create([
                'name' => "Evento {$number}",
                'title' => "Evento {$number}",
                'description' => 'Evento de prueba',
                'start_date' => now()->toDateString(),
                'end_date' => now()->toDateString(),
                'visibility' => 1,
                'active' => true,
            ]);
        }

        $this->get(route('eventos.index'))
            ->assertOk()
            ->assertSeeText('Evento 6')
            ->assertSeeText('Evento 2')
            ->assertDontSeeText('Evento 1');

        $response = $this->getJson(route('eventos.index', ['page' => 2]), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertOk();
        $this->assertStringContainsString('Evento 1', $response->json('html'));
    }

    public function test_ingredients_load_twenty_items_and_ajax_returns_more(): void
    {
        foreach (range(1, 21) as $number) {
            $name = sprintf('Ingrediente %02d', $number);

            Ingredient::create([
                'name' => $name,
                'normalized_name' => strtolower(str_replace(' ', '-', $name)),
                'category' => 'Verdura',
            ]);
        }

        $this->get(route('ingredientes.index'))
            ->assertOk()
            ->assertSeeText('Ingrediente 01')
            ->assertSeeText('Ingrediente 20')
            ->assertDontSeeText('Ingrediente 21');

        $response = $this->getJson(route('ingredientes.index', ['page' => 2]), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertOk();
        $this->assertStringContainsString('Ingrediente 21', $response->json('html'));
    }

    private function createRecipeFor(User $user, string $name): Recipe
    {
        $recipe = new Recipe();
        $recipe->name = $name;
        $recipe->description = 'Receta para probar paginacion';
        $recipe->time = 20;
        $recipe->tags = 'test';
        $recipe->visibility = 1;
        $recipe->image = 'img/recipes/test.jpg';
        $recipe->user_id = $user->id;
        $recipe->save();

        return $recipe;
    }
}
