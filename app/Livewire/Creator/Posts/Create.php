<?php

declare(strict_types=1);

namespace App\Livewire\Creator\Posts;

use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Create extends Component
{
    use WithFileUploads;

    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('nullable|string|max:500')]
    public string $excerpt = '';

    #[Validate('required|string')]
    public string $body = '';

    #[Validate('required|image|mimes:jpg,jpeg,png,webp|max:6144')]
    public ?UploadedFile $coverImageFile = null;

    public bool $isPremium = false;

    #[Validate('required|in:draft,published')]
    public string $status = 'draft';

    public function save(): void
    {
        try {
            $this->validate();
        } catch (ValidationException $e) {
            $errors = collect($e->validator->errors()->all())->join('. ');
            $this->dispatch('toast', type: 'error', message: $errors);

            return;
        }

        $coverImagePath = null;

        if ($this->coverImageFile instanceof UploadedFile) {
            $coverImagePath = $this->coverImageFile->store('posts/covers', 'public');
        }

        Post::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'excerpt' => $this->excerpt !== '' ? $this->excerpt : Str::limit(strip_tags($this->body), 220),
            'slug' => $this->generateUniqueSlug($this->title),
            'body' => $this->body,
            'cover_image' => $coverImagePath,
            'is_premium' => $this->isPremium,
            'status' => $this->status,
            'published_at' => $this->status === 'published' ? now() : null,
        ]);

        session()->flash('toast', [
            'type' => 'success',
            'message' => __('ui.post_created_successfully'),
        ]);

        $this->redirect(route('creator.posts.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.creator.posts.create');
    }

    private function generateUniqueSlug(string $title): string
    {
        $base = Str::slug($title);

        if ($base === '') {
            $base = 'post';
        }

        $slug = $base;
        $counter = 2;

        while (Post::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
