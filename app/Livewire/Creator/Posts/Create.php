<?php

declare(strict_types=1);

namespace App\Livewire\Creator\Posts;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
class Create extends Component
{
    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('required|string')]
    public string $body = '';

    public bool $isPremium = false;

    #[Validate('required|in:draft,published')]
    public string $status = 'draft';

    public function save(): void
    {
        $this->validate();

        Post::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'slug' => Str::slug($this->title).'-'.Str::random(4),
            'body' => $this->body,
            'is_premium' => $this->isPremium,
            'status' => $this->status,
            'published_at' => $this->status === 'published' ? now() : null,
        ]);

        $this->redirect(route('creator.posts.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.creator.posts.create');
    }
}
