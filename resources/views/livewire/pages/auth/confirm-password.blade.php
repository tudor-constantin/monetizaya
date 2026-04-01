<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $password = '';

    public function confirmPassword(): void
    {
        try {
            $this->validate(['password' => ['required', 'string']]);
        } catch (ValidationException $e) {
            $this->dispatch('toast', type: 'error', message: __('ui.password_required'));

            throw $e;
        }

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            $this->dispatch('toast', type: 'error', message: __('auth.password'));
            throw ValidationException::withMessages(['password' => __('auth.password')]);
        }

        session(['auth.password_confirmed_at' => time()]);
        $this->dispatch('toast', type: 'success', message: 'Password confirmed.');
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <form wire:submit="confirmPassword">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('ui.confirm_password_title') }}</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('ui.confirm_password_desc') }}</p>
        </div>

        <div class="space-y-5">
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('ui.password') }}</label>
                <input wire:model.defer="password" id="password" type="password" name="password" required autocomplete="current-password" class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm px-4 py-2.5" placeholder="{{ __('ui.placeholder_enter_password') }}">
            </div>
        </div>

        <button type="submit" class="mt-6 w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 focus:ring-blue-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled">
            <span wire:loading.remove>{{ __('ui.confirm') }}</span>
            <span wire:loading>Confirming...</span>
        </button>
    </form>
</div>
