<?php

namespace Tests\Feature;

use App\Models\Like;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserIndexEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_nav_profile_link_points_to_public_user_profile(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('index'))
            ->assertOk()
            ->assertSee(route('profile', ['id' => $user->id]), false);
    }

    public function test_own_profile_edit_user_link_points_to_user_edit_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('profile', ['id' => $user->id]))
            ->assertOk()
            ->assertSeeText('Editar usuario')
            ->assertSee(route('users.editCurrent'), false);
    }

    public function test_users_index_shows_user_search(): void
    {
        $viewer = User::factory()->create();
        $otherUser = User::factory()->create([
            'name' => 'Usuario Buscado',
            'username' => 'buscado',
        ]);

        $this->actingAs($viewer)
            ->get(route('users.index'))
            ->assertOk()
            ->assertSeeText('Usuarios')
            ->assertSeeText('Usuario Buscado')
            ->assertSee(route('profile', ['id' => $otherUser->id]), false)
            ->assertDontSeeText('Editar usuario');
    }

    public function test_user_edit_page_shows_edit_form_to_logged_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('users.editCurrent'))
            ->assertOk()
            ->assertSeeText('Editar usuario')
            ->assertSee(route('users.updateCurrent'), false);
    }

    public function test_user_edit_page_shows_delete_account_form(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('users.editCurrent'))
            ->assertOk()
            ->assertSeeText('Eliminar cuenta')
            ->assertSee(route('users.destroyCurrent'), false);
    }

    public function test_user_can_update_credentials_from_users_index(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password'),
        ]);

        $this->actingAs($user)
            ->put(route('users.updateCurrent'), [
                'name' => 'Nuevo Nombre',
                'username' => 'nuevo_usuario',
                'email' => 'nuevo@example.com',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ])
            ->assertRedirect();

        $user->refresh();

        $this->assertSame('Nuevo Nombre', $user->name);
        $this->assertSame('nuevo_usuario', $user->username);
        $this->assertSame('nuevo@example.com', $user->email);
        $this->assertTrue(Hash::check('new-password', $user->password));
    }

    public function test_user_can_delete_account_with_recipes_follows_likes_and_files(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'profile_photo' => 'img/users/profile/avatar.jpg',
        ]);
        $follower = User::factory()->create();
        $followed = User::factory()->create();
        $otherUser = User::factory()->create();

        Storage::disk('public')->put($user->profile_photo, 'avatar');
        Storage::disk('public')->put('img/recipes/cover/owned.jpg', 'cover');
        Storage::disk('public')->put('video/recipes/owned.mp4', 'video');
        Storage::disk('public')->put('img/recipes/cover/other.jpg', 'cover');

        $ownedRecipe = $this->createRecipeFor(
            $user,
            'Receta a borrar',
            'img/recipes/cover/owned.jpg',
            'video/recipes/owned.mp4'
        );
        $otherRecipe = $this->createRecipeFor($otherUser, 'Receta externa', 'img/recipes/cover/other.jpg');

        $follower->following()->attach($user->id);
        $user->following()->attach($followed->id);
        Like::create(['user_id' => $user->id, 'recipe_id' => $otherRecipe->id]);
        Like::create(['user_id' => $follower->id, 'recipe_id' => $ownedRecipe->id]);

        $this->actingAs($user)
            ->delete(route('users.destroyCurrent'))
            ->assertRedirect(route('index'));

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('recipes', ['id' => $ownedRecipe->id]);
        $this->assertDatabaseHas('users', ['id' => $otherUser->id]);
        $this->assertDatabaseHas('recipes', ['id' => $otherRecipe->id]);
        $this->assertSame(0, DB::table('follows')->where('follower_id', $user->id)->orWhere('following_id', $user->id)->count());
        $this->assertSame(0, DB::table('likes')->where('user_id', $user->id)->orWhere('recipe_id', $ownedRecipe->id)->count());
        Storage::disk('public')->assertMissing('img/users/profile/avatar.jpg');
        Storage::disk('public')->assertMissing('img/recipes/cover/owned.jpg');
        Storage::disk('public')->assertMissing('video/recipes/owned.mp4');
        Storage::disk('public')->assertExists('img/recipes/cover/other.jpg');
    }

    private function createRecipeFor(User $user, string $name, string $image, ?string $video = null): Recipe
    {
        $recipe = new Recipe();
        $recipe->name = $name;
        $recipe->description = 'Receta sencilla para probar usuarios';
        $recipe->time = 20;
        $recipe->tags = 'prueba';
        $recipe->visibility = 1;
        $recipe->image = $image;
        $recipe->video = $video;
        $recipe->user_id = $user->id;
        $recipe->save();

        return $recipe;
    }
}
