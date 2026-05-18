<?php

namespace Tests\Feature;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserProfilePhotoTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_profile_photo_when_signing_up(): void
    {
        Storage::fake('public');

        $this->post(route('auth.signup.post'), [
            'name' => 'Foto Usuario',
            'username' => 'foto_usuario',
            'email' => 'foto@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'profile_photo' => UploadedFile::fake()->image('avatar.jpg'),
        ])->assertRedirect(route('index'));

        $user = User::where('email', 'foto@example.com')->firstOrFail();

        $this->assertNotNull($user->profile_photo);
        Storage::disk('public')->assertExists($user->profile_photo);
    }

    public function test_user_can_update_profile_photo_from_users_index(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $this->actingAs($user)
            ->put(route('users.updateCurrent'), [
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'profile_photo' => UploadedFile::fake()->image('updated-avatar.jpg'),
            ])
            ->assertRedirect();

        $user->refresh();

        $this->assertNotNull($user->profile_photo);
        Storage::disk('public')->assertExists($user->profile_photo);
    }

    public function test_profile_and_recipe_feed_show_user_profile_photo(): void
    {
        $user = User::factory()->create([
            'profile_photo' => 'img/users/profile/avatar.jpg',
        ]);

        $recipe = new Recipe();
        $recipe->name = 'Arroz';
        $recipe->description = 'Receta publica con autor';
        $recipe->time = 25;
        $recipe->tags = 'arroz';
        $recipe->visibility = 1;
        $recipe->image = 'img/recipes/cover/arroz.jpg';
        $recipe->user_id = $user->id;
        $recipe->save();

        $this->get(route('profile', ['id' => $user->id]))
            ->assertOk()
            ->assertSee('storage/' . $user->profile_photo, false);

        $this->get(route('index'))
            ->assertOk()
            ->assertSee('storage/' . $user->profile_photo, false);

        $this->get(route('recetas.show', ['receta' => $recipe->id]))
            ->assertOk()
            ->assertSee('storage/' . $user->profile_photo, false);
    }

    public function test_user_search_returns_profile_photo_url(): void
    {
        $user = User::factory()->create([
            'username' => 'buscado',
            'profile_photo' => 'img/users/profile/buscado.jpg',
        ]);

        $this->getJson(route('users.search', ['q' => 'buscado']))
            ->assertOk()
            ->assertJsonFragment([
                'id' => $user->id,
                'username' => 'buscado',
            ])
            ->assertJsonFragment([
                'profile_photo_url' => $user->profilePhotoUrl(),
            ]);
    }
}
