<?php

namespace Tests\Feature;

use App\Livewire\Creator\Posts\Create;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CreatorPostSlugTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_post_generates_unique_slug_when_soft_deleted_slug_exists(): void
    {
        Storage::fake('public');

        Role::findOrCreate('creator', 'web');

        /** @var User $creator */
        $creator = User::factory()->create();
        $creator->assignRole('creator');

        Post::factory()->create([
            'user_id' => $creator->id,
            'title' => 'Hello world',
            'slug' => 'hello-world',
        ])->delete();

        $this->actingAs($creator);

        Livewire::test(Create::class)
            ->set('title', 'Hello world')
            ->set('excerpt', 'Excerpt')
            ->set('body', 'Body text for this post')
            ->set('coverImageFile', UploadedFile::fake()->create('cover.jpg', 120, 'image/jpeg'))
            ->set('status', 'published')
            ->call('save')
            ->assertRedirect(route('creator.posts.index', absolute: false));

        $this->assertDatabaseHas('posts', [
            'user_id' => $creator->id,
            'slug' => 'hello-world-2',
        ]);
    }
}
