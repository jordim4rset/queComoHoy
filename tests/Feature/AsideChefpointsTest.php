<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AsideChefpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_logged_user_sees_chefpoints_widget_in_aside(): void
    {
        $user = User::factory()->create([
            'chefpoints' => 1250,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('index'));

        $response
            ->assertOk()
            ->assertSee('ChefPoints')
            ->assertSee('1250')
            ->assertSee(route('shop'), false)
            ->assertSee('Nivel 2');
    }

    public function test_guest_does_not_see_chefpoints_widget_in_aside(): void
    {
        $response = $this->get(route('index'));

        $response
            ->assertOk()
            ->assertDontSee('ChefPoints');
    }
}
