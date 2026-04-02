<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        try {
            $this->validate();
            $this->form->authenticate();
            Session::regenerate();
        } catch (ValidationException $e) {
            $this->dispatch('toast', type: 'error', message: __('auth.failed'));

            throw $e;
        }

        session()->flash('toast', [
            'type' => 'success',
            'message' => __('ui.logged_in_successfully'),
        ]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <form wire:submit="login">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('ui.welcome_back') }}</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('ui.sign_in_to_account') }}</p>
        </div>

        <div class="space-y-5">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('ui.email') }}</label>
                <input wire:model.defer="form.email" id="email" type="email" name="email" required autofocus autocomplete="username" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm px-4 py-2.5" placeholder="{{ __('ui.placeholder_email') }}">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('ui.password') }}</label>
                <input wire:model.defer="form.password" id="password" type="password" name="password" required autocomplete="current-password" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm px-4 py-2.5" placeholder="{{ __('ui.placeholder_enter_password') }}">
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input wire:model.defer="form.remember" id="remember" type="checkbox" class="rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-blue-600 focus:border-blue-500 focus:ring-blue-500" name="remember">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('ui.remember_me') }}</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400" wire:navigate>{{ __('ui.forgot_password') }}</a>
                @endif
            </div>
        </div>

        <button type="submit" class="mt-6 w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 focus:ring-blue-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled">
            <span wire:loading.remove>{{ __('ui.sign_in') }}</span>
            <span wire:loading>{{ __('ui.signing_in') }}</span>
        </button>

        <p class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
            {{ __('ui.dont_have_account') }}
            <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400" wire:navigate>{{ __('ui.sign_up') }}</a>
        </p>
    </form>
</div>
