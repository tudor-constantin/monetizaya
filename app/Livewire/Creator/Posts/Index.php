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
    public ?int $confirmingPostDeletion = null;

    public function confirmDelete($postId): void
    {
        $this->confirmingPostDeletion = (int) $postId;
    }

    public function deletePost(): void
    {
        if (! $this->confirmingPostDeletion) {
            return;
        }

        $post = Post::where('user_id', Auth::id())->findOrFail($this->confirmingPostDeletion);
        $post->delete();

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Post deleted successfully.',
        ]);

        $this->confirmingPostDeletion = null;
        $this->redirect(route('creator.posts.index'));
    }

    public function render()
    {
        $posts = Post::where('user_id', Auth::id())->latest()->get();

        return view('livewire.creator.posts.index', ['posts' => $posts]);
    }
}
