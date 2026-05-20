<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventShowRecipesFeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_show_renders_related_recipes_like_feed_posts(): void
    {
        $author = User::factory()->create(['username' => 'chef_evento']);
        $event = Event::create([
            'name' => 'Semana Italiana',
            'title' => 'Semana Italiana',
            'description' => 'Recetas para el evento',
            'start_date' => now()->toDateString(),
            'end_date' => now()->toDateString(),
            'visibility' => 1,
            'active' => true,
        ]);
        $recipe = $this->createRecipeFor($author, 'Pasta del evento');

        $event->recipes()->attach($recipe->id);

        $this->get(route('eventos.show', ['event' => $event]))
            ->assertOk()
            ->assertSee('class="post"', false)
            ->assertSee('class="icon-stat like-btn"', false)
            ->assertSee('class="stat comment-toggle"', false)
            ->assertSeeText('Pasta del evento')
            ->assertSeeText('chef_evento')
            ->assertDontSee('<ul>', false);
    }

    private function createRecipeFor(User $user, string $name): Recipe
    {
        $recipe = new Recipe();
        $recipe->name = $name;
        $recipe->description = 'Receta asociada a un evento';
        $recipe->time = 20;
        $recipe->tags = 'evento';
        $recipe->visibility = 1;
        $recipe->image = 'img/recipes/evento.jpg';
        $recipe->user_id = $user->id;
        $recipe->save();

        return $recipe;
    }
}
