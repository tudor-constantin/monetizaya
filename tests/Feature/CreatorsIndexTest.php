<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CreatorsIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_see_admin_with_published_posts_in_creators_index(): void
    {
        Role::findOrCreate('admin', 'web');
        Role::findOrCreate('user', 'web');

        /** @var User $admin */
        $admin = User::factory()->create([
            'is_active' => true,
            'is_public' => true,
        ]);
        $admin->assignRole('admin');

        Post::factory()->published()->free()->create([
            'user_id' => $admin->id,
        ]);

        /** @var User $viewer */
        $viewer = User::factory()->create();
        $viewer->assignRole('user');

        $response = $this->actingAs($viewer)->get(route('creators.index'));

        $response
            ->assertOk()
            ->assertSee($admin->name);
    }
}
