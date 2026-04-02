<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PublicPremiumPostAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_sees_locked_preview_and_subscribe_call_to_action(): void
    {
        Role::findOrCreate('creator', 'web');

        /** @var User $creator */
        $creator = User::factory()->create([
            'is_active' => true,
            'is_public' => true,
            'subscription_price' => 14.99,
        ]);
        $creator->assignRole('creator');

        $body = str_repeat('Premium content line. ', 80);

        /** @var Post $post */
        $post = Post::factory()->published()->premium()->create([
            'user_id' => $creator->id,
            'body' => $body,
        ]);

        $response = $this->get(route('creators.posts.show', ['user' => $creator, 'post' => $post]));

        $response->assertOk();
        $response->assertSee(__('ui.premium_article_locked_title'));
        $response->assertSee(__('ui.subscribe'));
        $response->assertDontSee($body);
        $response->assertDontSee('href="'.url('/creators/'.$creator->slug.'/posts').'"', false);
    }
}
