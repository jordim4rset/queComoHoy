<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
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
}
