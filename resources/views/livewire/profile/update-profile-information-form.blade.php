<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $email = '';

    public $avatarFile = null;

    public ?string $avatarPreview = null;

    public function mount(): void
    {
        /** @var User $user */
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->avatarPreview = $user->avatar_url;
    }

    public function updateProfileInformation(): void
    {
        /** @var User $user */
        $user = Auth::user();
        try {
            $validated = $this->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
                'avatarFile' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ]);
        } catch (ValidationException $e) {
            $errors = collect($e->validator->errors()->all())->join('. ');
            $this->dispatch('toast', type: 'error', message: $errors);

            return;
        }

        $avatarPath = $user->avatar;

        if ($this->avatarFile) {
            if ($avatarPath && ! Str::startsWith($avatarPath, ['http://', 'https://']) && Storage::disk('public')->exists($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }

            $avatarPath = $this->avatarFile->store('users/avatars', 'public');
        }

        $user->fill($validated);
        $user->avatar = $avatarPath;
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        Auth::setUser($user->fresh());

        $this->avatarPreview = $user->avatar_url;
        $this->avatarFile = null;
        $this->dispatch('toast', type: 'success', message: __('ui.profile_updated_successfully'));
        $this->dispatch('profile-updated');
    }

    public function sendVerification(): void
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }
        $user->sendEmailVerificationNotification();
        $this->dispatch('toast', type: 'success', message: __('ui.verification_link_sent'));
    }
}; ?>

<div>
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('ui.profile_information') }}</h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('ui.profile_information_desc') }}</p>
    </div>

    <form wire:submit="updateProfileInformation" class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('ui.avatar') }}</label>
            <div class="flex items-center gap-4">
                @if($avatarFile)
                    <img src="{{ $avatarFile->temporaryUrl() }}" alt="" class="w-16 h-16 rounded-full object-cover border border-gray-200 dark:border-gray-700">
                @elseif($avatarPreview)
                    <img src="{{ $avatarPreview }}" alt="" class="w-16 h-16 rounded-full object-cover border border-gray-200 dark:border-gray-700">
                @else
                    <div class="w-16 h-16 rounded-full bg-blue-600 flex items-center justify-center text-white text-xl font-bold">
                        {{ strtoupper(substr($name, 0, 1)) }}
                    </div>
                @endif

                <div class="flex-1">
                    <input wire:model="avatarFile" type="file" accept="image/png,image/jpeg,image/webp" class="block w-full cursor-pointer text-sm text-gray-600 dark:text-gray-300 file:cursor-pointer file:mr-3 file:rounded-lg file:border-0 file:bg-blue-600 hover:file:bg-blue-700 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-white transition-colors">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('ui.image_requirements_avatar') }}</p>
                </div>
            </div>
        </div>

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('ui.name') }}</label>
            <input wire:model.defer="name" id="name" name="name" type="text" required autofocus autocomplete="name" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm px-4 py-2.5" placeholder="{{ __('ui.placeholder_full_name') }}">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('ui.email') }}</label>
            <input wire:model.defer="email" id="email" name="email" type="email" required autocomplete="username" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm px-4 py-2.5" placeholder="{{ __('ui.placeholder_email') }}">

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 rounded-lg">
                    <p class="text-sm text-blue-800 dark:text-blue-300">
                        {{ __('ui.email_unverified') }}
                        <button wire:click.prevent="sendVerification" class="font-medium text-blue-900 dark:text-blue-200 underline hover:text-blue-700 dark:hover:text-blue-100">{{ __('ui.resend_verification_link') }}</button>
                    </p>
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ __('ui.save') }}</span>
                <span wire:loading>{{ __('ui.saving') }}</span>
            </button>
        </div>
    </form>
</div>
