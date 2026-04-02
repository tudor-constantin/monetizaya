<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PublicCreatorsAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_public_creators_index_profile_and_post(): void
    {
        Role::findOrCreate('creator', 'web');

        /** @var User $creator */
        $creator = User::factory()->create([
            'is_active' => true,
            'is_public' => true,
        ]);
        $creator->assignRole('creator');

        /** @var Post $post */
        $post = Post::factory()->published()->free()->create([
            'user_id' => $creator->id,
        ]);

        $this->get(route('creators.index'))
            ->assertOk()
            ->assertSee($creator->name);

        $this->get(route('creators.show', $creator))
            ->assertOk()
            ->assertSee($creator->name);

        $this->get(route('creators.posts.show', ['user' => $creator, 'post' => $post]))
            ->assertOk()
            ->assertSee($post->title);
    }
}
