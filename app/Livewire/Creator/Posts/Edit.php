<?php

declare(strict_types=1);

namespace App\Livewire\Creator\Posts;

use App\Models\Post;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
class Edit extends Component
{
    public Post $post;

    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('required|string')]
    public string $body = '';

    public bool $isPremium = false;

    #[Validate('required|in:draft,published,archived')]
    public string $status = 'draft';

    public function mount(Post $post): void
    {
        $this->authorize('update', $post);

        $this->post = $post;
        $this->title = $post->title;
        $this->body = $post->body;
        $this->isPremium = $post->is_premium;
        $this->status = $post->status;
    }

    public function save(): void
    {
        try {
            $this->validate();
        } catch (ValidationException $e) {
            $errors = collect($e->validator->errors()->all())->join('. ');
            $this->dispatch('toast', type: 'error', message: $errors);

            return;
        }

        $this->post->update([
            'title' => $this->title,
            'slug' => $this->post->slug,
            'body' => $this->body,
            'is_premium' => $this->isPremium,
            'status' => $this->status,
            'published_at' => $this->status === 'published' && ! $this->post->published_at ? now() : $this->post->published_at,
        ]);

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Post updated successfully.',
        ]);

        $this->redirect(route('creator.posts.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.creator.posts.edit');
    }
}
