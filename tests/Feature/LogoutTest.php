<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_logged_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('index'));

        $this->assertGuest();
    }

    public function test_nav_renders_logout_form_for_logged_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('index'))
            ->assertOk()
            ->assertSee('action="' . route('logout', [], false) . '"', false)
            ->assertSee('method="POST"', false)
            ->assertSeeText('Cerrar Sesion');
    }
}
