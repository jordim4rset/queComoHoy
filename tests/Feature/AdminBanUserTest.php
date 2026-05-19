<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminBanUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_ban_and_unban_a_user(): void
    {
        $admin = User::factory()->create([
            'rol' => 'admin',
        ]);
        $user = User::factory()->create();

        $this->actingAs($admin)
            ->post(route('users.ban', $user))
            ->assertRedirect();

        $this->assertNotNull($user->fresh()->banned_at);

        $this->actingAs($admin)
            ->post(route('users.unban', $user))
            ->assertRedirect();

        $this->assertNull($user->fresh()->banned_at);
    }

    public function test_non_admin_cannot_ban_users(): void
    {
        $member = User::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($member)
            ->post(route('users.ban', $user))
            ->assertForbidden();

        $this->assertNull($user->fresh()->banned_at);
    }

    public function test_admin_cannot_ban_themselves(): void
    {
        $admin = User::factory()->create([
            'rol' => 'admin',
        ]);

        $this->actingAs($admin)
            ->post(route('users.ban', $admin))
            ->assertSessionHasErrors('ban');

        $this->assertNull($admin->fresh()->banned_at);
    }

    public function test_banned_user_cannot_login_with_email(): void
    {
        $user = User::factory()->create([
            'email' => 'banned@example.com',
            'password' => Hash::make('password123'),
            'banned_at' => now(),
        ]);

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password123',
        ])
            ->assertRedirect()
            ->assertSessionHasErrors([
                'email' => 'Tu usuario esta baneado por tiempo indefinido, por eso no puedes acceder con tus credenciales.',
            ]);

        $this->assertGuest();
    }

    public function test_banned_user_cannot_login_with_username(): void
    {
        $user = User::factory()->create([
            'username' => 'usuario_baneado',
            'password' => Hash::make('password123'),
            'banned_at' => now(),
        ]);

        $this->post(route('login'), [
            'email' => $user->username,
            'password' => 'password123',
        ])
            ->assertRedirect()
            ->assertSessionHasErrors([
                'email' => 'Tu usuario esta baneado por tiempo indefinido, por eso no puedes acceder con tus credenciales.',
            ]);

        $this->assertGuest();
    }
}
