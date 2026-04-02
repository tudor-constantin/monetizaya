<?php

use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    public function sendPasswordResetLink(): void
    {
        try {
            $this->validate(['email' => ['required', 'string', 'email']]);
        } catch (ValidationException $e) {
            $errors = collect($e->validator->errors()->all())->join('. ');
            $this->dispatch('toast', type: 'error', message: $errors);

            return;
        }

        $status = Password::sendResetLink($this->only('email'));
        if ($status != Password::RESET_LINK_SENT) {
            $this->dispatch('toast', type: 'error', message: __($status));

            return;
        }

        $this->reset('email');
        session()->flash('toast', [
            'type' => 'success',
            'message' => __($status),
        ]);
    }
}; ?>

<div>
    <form wire:submit="sendPasswordResetLink">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('ui.reset_password') }}</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('ui.reset_password_desc') }}</p>
        </div>

        <div class="space-y-5">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('ui.email') }}</label>
                <input wire:model.defer="email" id="email" type="email" name="email" required autofocus class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm px-4 py-2.5" placeholder="{{ __('ui.placeholder_email') }}">
            </div>
        </div>

        <button type="submit" class="mt-6 w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 focus:ring-blue-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled">
            <span wire:loading.remove>{{ __('ui.send_reset_link') }}</span>
            <span wire:loading>Sending...</span>
        </button>

        <p class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
            {{ __('ui.remember_password') }}
            <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400" wire:navigate>{{ __('ui.sign_in') }}</a>
        </p>
    </form>
</div>
