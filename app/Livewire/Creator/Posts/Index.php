<?php

declare(strict_types=1);

namespace App\Livewire\Creator\Posts;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    public function delete($postId): void
    {
        $post = Post::where('user_id', Auth::id())->findOrFail($postId);
        $post->delete();
    }

    public function render()
    {
        $posts = Post::where('user_id', Auth::id())->latest()->get();

        return view('livewire.creator.posts.index', ['posts' => $posts]);
    }
}
