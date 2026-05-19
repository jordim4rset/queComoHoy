<?php

namespace Tests\Feature;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RecipeMediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_recipe_form_splits_cover_and_video_uploads(): void
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('recetas.create'));

        $response
            ->assertOk()
            ->assertSee('Subir portada')
            ->assertSee('Subir video')
            ->assertSee('name="image"', false)
            ->assertSee('name="video"', false);
    }

    public function test_recipe_can_store_a_cover_image_and_one_video(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $cover = UploadedFile::fake()->image('cover.jpg');
        $video = UploadedFile::fake()->create('video.mp4', 512, 'video/mp4');

        $response = $this
            ->actingAs($user)
            ->post(route('recetas.store'), [
                'name' => 'Boquerones',
                'description' => 'Una receta con portada y video.',
                'time' => 15,
                'tags' => 'pescado',
                'visibility' => 'on',
                'image' => $cover,
                'video' => $video,
            ]);

        $response->assertRedirect(route('recetas.index'));

        $recipe = Recipe::firstOrFail();

        $this->assertNotNull($recipe->image);
        $this->assertNotNull($recipe->video);
        Storage::disk('public')->assertExists($recipe->image);
        Storage::disk('public')->assertExists($recipe->video);
    }

    public function test_recipe_show_renders_video_slider_when_recipe_has_video(): void
    {
        $user = User::factory()->create();
        $recipe = new Recipe();
        $recipe->name = 'Boquerones';
        $recipe->description = 'Una receta con portada y video.';
        $recipe->time = 15;
        $recipe->tags = 'pescado';
        $recipe->visibility = 1;
        $recipe->image = 'img/recipes/cover/boquerones.jpg';
        $recipe->video = 'video/recipes/boquerones.mp4';
        $recipe->user_id = $user->id;
        $recipe->save();

        $response = $this->get(route('recetas.show', ['receta' => $recipe->id]));

        $response
            ->assertOk()
            ->assertSee('data-recipe-media', false)
            ->assertSee('video/recipes/boquerones.mp4');
    }
}
