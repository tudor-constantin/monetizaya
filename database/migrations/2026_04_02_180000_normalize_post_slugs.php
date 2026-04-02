<?php

use App\Models\Post;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Post::query()->orderBy('id')->get()->each(function (Post $post): void {
            $base = Str::slug($post->title);

            if ($base === '') {
                $base = 'post';
            }

            $slug = $base;
            $counter = 2;

            while (Post::query()->where('id', '!=', $post->id)->where('slug', $slug)->exists()) {
                $slug = $base.'-'.$counter;
                $counter++;
            }

            if ($post->slug !== $slug) {
                $post->forceFill(['slug' => $slug])->save();
            }
        });
    }

    public function down(): void
    {
        // noop
    }
};
