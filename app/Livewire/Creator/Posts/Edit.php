<?php

declare(strict_types=1);

namespace App\Livewire\Creator\Posts;

use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Edit extends Component
{
    use WithFileUploads;

    public Post $post;

    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('nullable|string|max:500')]
    public string $excerpt = '';

    #[Validate('required|string')]
    public string $body = '';

    #[Validate('nullable|image|mimes:jpg,jpeg,png,webp|max:6144')]
    public ?UploadedFile $coverImageFile = null;

    public ?string $coverImageUrl = null;

    public bool $isPremium = false;

    #[Validate('required|in:draft,published,archived')]
    public string $status = 'draft';

    public function mount(Post $post): void
    {
        $this->authorize('update', $post);

        $this->post = $post;
        $this->title = $post->title;
        $this->excerpt = (string) ($post->excerpt ?? '');
        $this->body = $post->body;
        $this->isPremium = $post->is_premium;
        $this->status = $post->status;
        $this->coverImageUrl = $this->resolveCoverUrl($post->cover_image);
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

        $coverImagePath = $this->post->cover_image;

        if ($this->coverImageFile instanceof UploadedFile) {
            if ($coverImagePath && ! Str::startsWith($coverImagePath, ['http://', 'https://']) && Storage::disk('public')->exists($coverImagePath)) {
                Storage::disk('public')->delete($coverImagePath);
            }

            $coverImagePath = $this->coverImageFile->store('posts/covers', 'public');
        }

        $this->post->update([
            'title' => $this->title,
            'excerpt' => $this->excerpt !== '' ? $this->excerpt : Str::limit(strip_tags($this->body), 220),
            'slug' => $this->generateUniqueSlug($this->title, $this->post->id),
            'body' => $this->body,
            'cover_image' => $coverImagePath,
            'is_premium' => $this->isPremium,
            'status' => $this->status,
            'published_at' => $this->status === 'published' && ! $this->post->published_at ? now() : $this->post->published_at,
        ]);

        $this->coverImageUrl = $this->resolveCoverUrl($coverImagePath);
        $this->coverImageFile = null;

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

    private function resolveCoverUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return asset('storage/'.$path);
    }

    private function generateUniqueSlug(string $title, int $currentPostId): string
    {
        $base = Str::slug($title);

        if ($base === '') {
            $base = 'post';
        }

        $slug = $base;
        $counter = 2;

        while (Post::query()->where('id', '!=', $currentPostId)->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
