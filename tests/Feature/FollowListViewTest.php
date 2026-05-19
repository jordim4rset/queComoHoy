<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FollowListViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_followers_page_renders_styled_user_list(): void
    {
        $user = User::factory()->create(['username' => 'perfil']);
        $follower = User::factory()->create(['name' => 'Seguidor Uno', 'username' => 'seguidor']);

        $follower->following()->attach($user->id);

        $this->get(route('user.followers', $user->id))
            ->assertOk()
            ->assertSeeText('Seguidores')
            ->assertSeeText('Seguidor Uno')
            ->assertSee('follow-user-main', false);
    }

    public function test_following_page_renders_styled_user_list(): void
    {
        $user = User::factory()->create(['username' => 'perfil']);
        $followed = User::factory()->create(['name' => 'Usuario Seguido', 'username' => 'seguido']);

        $user->following()->attach($followed->id);

        $this->get(route('user.following', $user->id))
            ->assertOk()
            ->assertSeeText('Seguidos')
            ->assertSeeText('Usuario Seguido')
            ->assertSee('follow-user-main', false);
    }
}
