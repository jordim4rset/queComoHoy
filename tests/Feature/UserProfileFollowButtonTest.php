<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileFollowButtonTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_does_not_see_follow_button_on_profile(): void
    {
        $profileUser = User::factory()->create();

        $this->get(route('profile', $profileUser->id))
            ->assertOk()
            ->assertDontSeeText('Seguir')
            ->assertDontSeeText('Dejar de seguir');
    }

    public function test_logged_user_sees_follow_button_when_not_following_profile(): void
    {
        $viewer = User::factory()->create();
        $profileUser = User::factory()->create();

        $this->actingAs($viewer)
            ->get(route('profile', $profileUser->id))
            ->assertOk()
            ->assertSeeText('Seguir')
            ->assertDontSeeText('Dejar de seguir');
    }

    public function test_logged_user_sees_unfollow_button_when_already_following_profile(): void
    {
        $viewer = User::factory()->create();
        $profileUser = User::factory()->create();

        $viewer->following()->attach($profileUser->id);

        $this->actingAs($viewer)
            ->get(route('profile', $profileUser->id))
            ->assertOk()
            ->assertSeeText('Dejar de seguir');
    }

    public function test_profile_following_count_links_to_following_users_page(): void
    {
        $profileUser = User::factory()->create();

        $this->get(route('profile', $profileUser->id))
            ->assertOk()
            ->assertSee(route('user.following', $profileUser->id), false);
    }
}
