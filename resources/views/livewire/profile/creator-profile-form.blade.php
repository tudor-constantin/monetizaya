<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public string $bio = '';

    public string $tagline = '';

    public ?UploadedFile $avatarFile = null;

    public ?UploadedFile $coverFile = null;

    public ?string $avatarPreview = null;

    public ?string $coverPreview = null;

    public string $subscriptionPrice = '';

    public string $website = '';

    public string $twitter = '';

    public string $instagram = '';

    public string $youtube = '';

    public bool $isPublic = false;

    public function mount(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $this->bio = (string) ($user->bio ?? '');
        $this->tagline = (string) ($user->tagline ?? '');
        $this->avatarPreview = $this->resolveMediaUrl($user->avatar);
        $this->coverPreview = $this->resolveMediaUrl($user->cover_image);
        $this->subscriptionPrice = (string) number_format($user->getActiveSubscriptionPrice(), 2, '.', '');
        $this->website = (string) data_get($user->social_links, 'website', '');
        $this->twitter = (string) data_get($user->social_links, 'twitter', '');
        $this->instagram = (string) data_get($user->social_links, 'instagram', '');
        $this->youtube = (string) data_get($user->social_links, 'youtube', '');
        $this->isPublic = (bool) $user->is_public;
    }

    public function saveCreatorProfile(): void
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user->hasRole('creator') && ! $user->hasRole('admin')) {
            $this->dispatch('toast', type: 'error', message: __('ui.creator_profile_role_required'));

            return;
        }

        if (! $user->avatar && ! $this->avatarFile) {
            $this->dispatch('toast', type: 'error', message: __('ui.creator_avatar_required'));

            return;
        }

        try {
            $validated = $this->validate([
                'bio' => ['nullable', 'string', 'max:600'],
                'tagline' => ['nullable', 'string', 'max:140'],
                'avatarFile' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
                'coverFile' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
                'subscriptionPrice' => ['required', 'numeric', 'min:1', 'max:9999'],
                'website' => ['nullable', 'url', 'max:255'],
                'twitter' => ['nullable', 'url', 'max:255'],
                'instagram' => ['nullable', 'url', 'max:255'],
                'youtube' => ['nullable', 'url', 'max:255'],
            ]);
        } catch (ValidationException $e) {
            $errors = collect($e->validator->errors()->all())->join('. ');
            $this->dispatch('toast', type: 'error', message: $errors);

            return;
        }

        $avatarPath = $user->avatar;
        if ($validated['avatarFile'] instanceof UploadedFile) {
            $this->deleteLocalMedia($user->avatar);
            $avatarPath = $validated['avatarFile']->store('creator/avatars', 'public');
        }

        $coverPath = $user->cover_image;
        if ($validated['coverFile'] instanceof UploadedFile) {
            $this->deleteLocalMedia($user->cover_image);
            $coverPath = $validated['coverFile']->store('creator/covers', 'public');
        }

        $socialLinks = array_filter([
            'website' => $validated['website'] ?: null,
            'twitter' => $validated['twitter'] ?: null,
            'instagram' => $validated['instagram'] ?: null,
            'youtube' => $validated['youtube'] ?: null,
        ]);

        $user->forceFill([
            'bio' => $validated['bio'] ?: null,
            'tagline' => $validated['tagline'] ?: null,
            'avatar' => $avatarPath,
            'cover_image' => $coverPath,
            'subscription_price' => (float) $validated['subscriptionPrice'],
            'social_links' => $socialLinks,
            'is_creator' => $user->hasRole('creator') || $user->hasRole('admin'),
            'is_public' => $this->isPublic,
        ])->save();

        Auth::setUser($user->fresh());

        $this->avatarPreview = $this->resolveMediaUrl($avatarPath);
        $this->coverPreview = $this->resolveMediaUrl($coverPath);
        $this->avatarFile = null;
        $this->coverFile = null;

        $this->dispatch('toast', type: 'success', message: __('ui.creator_profile_saved'));
        $this->dispatch('profile-updated');
    }

    private function resolveMediaUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return asset('storage/'.$path);
    }

    private function deleteLocalMedia(?string $path): void
    {
        if (! $path || Str::startsWith($path, ['http://', 'https://'])) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}; ?>

<div>
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('ui.creator_profile_settings') }}</h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('ui.creator_profile_settings_desc') }}</p>
    </div>

    <form wire:submit="saveCreatorProfile" class="space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('ui.creator_tagline') }}</label>
                <input wire:model.defer="tagline" type="text" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm px-4 py-2.5" placeholder="{{ __('ui.creator_tagline_placeholder') }}">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('ui.subscription_price_monthly') }}</label>
                <input wire:model.defer="subscriptionPrice" type="number" min="1" step="0.01" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm px-4 py-2.5" placeholder="12.00">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('ui.bio') }}</label>
                <textarea wire:model.defer="bio" rows="4" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm px-4 py-2.5" placeholder="{{ __('ui.bio_placeholder') }}"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Website</label>
                <input wire:model.defer="website" type="url" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm px-4 py-2.5" placeholder="https://...">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Twitter / X</label>
                <input wire:model.defer="twitter" type="url" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm px-4 py-2.5" placeholder="https://x.com/...">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Instagram</label>
                <input wire:model.defer="instagram" type="url" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm px-4 py-2.5" placeholder="https://instagram.com/...">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">YouTube</label>
                <input wire:model.defer="youtube" type="url" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm px-4 py-2.5" placeholder="https://youtube.com/...">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('ui.avatar') }}</p>
                <div class="mt-3">
                    <div class="h-24 w-24 rounded-xl overflow-hidden bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-300 flex items-center justify-center text-2xl font-bold">
                        @if($avatarFile)
                            <img src="{{ $avatarFile->temporaryUrl() }}" alt="" class="w-full h-full object-cover">
                        @elseif($avatarPreview)
                            <img src="{{ $avatarPreview }}" alt="" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <input wire:model="avatarFile" type="file" accept="image/png,image/jpeg,image/webp" class="mt-3 block w-full cursor-pointer text-sm text-gray-600 dark:text-gray-300 file:cursor-pointer file:mr-3 file:rounded-lg file:border-0 file:bg-blue-600 hover:file:bg-blue-700 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-white transition-colors">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('ui.image_requirements_avatar') }}</p>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('ui.cover') }}</p>
                <div class="mt-3">
                    <div class="h-24 rounded-lg bg-gradient-to-r from-blue-800 to-blue-600 overflow-hidden">
                        @if($coverFile)
                            <img src="{{ $coverFile->temporaryUrl() }}" alt="" class="w-full h-full object-cover">
                        @elseif($coverPreview)
                            <img src="{{ $coverPreview }}" alt="" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <input wire:model="coverFile" type="file" accept="image/png,image/jpeg,image/webp" class="mt-3 block w-full cursor-pointer text-sm text-gray-600 dark:text-gray-300 file:cursor-pointer file:mr-3 file:rounded-lg file:border-0 file:bg-blue-600 hover:file:bg-blue-700 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-white transition-colors">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('ui.image_requirements_cover') }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-4">
            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('ui.profile_visibility') }}</p>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('ui.profile_visibility_desc') }}</p>
            <div class="mt-3 flex items-center gap-3">
                <button type="button" role="switch" @click="$wire.isPublic = !$wire.isPublic" :aria-checked="$wire.isPublic" class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" :class="$wire.isPublic ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-600'">
                    <span class="sr-only">{{ __('ui.toggle_visibility') }}</span>
                    <span class="pointer-events-none inline-block h-5 w-5 translate-x-0 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="$wire.isPublic ? 'translate-x-5' : 'translate-x-0'"></span>
                </button>
                <span class="text-sm font-medium" :class="$wire.isPublic ? 'text-blue-700 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400'" x-text="$wire.isPublic ? '{{ __('ui.profile_public') }}' : '{{ __('ui.profile_private') }}'"></span>
            </div>
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled">
                {{ __('ui.save_profile') }}
            </button>
            <a href="{{ route('creators.show', auth()->user()) }}" class="text-sm font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400" wire:navigate>
                {{ __('ui.view_public_profile') }}
            </a>
        </div>
    </form>
</div>
