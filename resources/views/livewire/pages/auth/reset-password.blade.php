<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Locked]
    public string $token = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(string $token): void
    {
        $this->token = $token;
        $this->email = request()->string('email');
    }

    public function resetPassword(): void
    {
        try {
            $this->validate([
                'token' => ['required'],
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            ]);
        } catch (ValidationException $e) {
            $errors = collect($e->validator->errors()->all())->join('. ');
            $this->dispatch('toast', type: 'error', message: $errors);

            return;
        }

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();
                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            $this->dispatch('toast', type: 'error', message: __($status));

            return;
        }

        Session::flash('status', __($status));
        session()->flash('toast', [
            'type' => 'success',
            'message' => __($status),
        ]);
        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div>
    <form wire:submit="resetPassword">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('ui.set_new_password') }}</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('ui.set_new_password_desc') }}</p>
        </div>

        <div class="space-y-5">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('ui.email') }}</label>
                <input wire:model.defer="email" id="email" type="email" name="email" required autofocus autocomplete="username" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm px-4 py-2.5" placeholder="{{ __('ui.placeholder_email') }}">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('ui.new_password') }}</label>
                <input wire:model.defer="password" id="password" type="password" name="password" required autocomplete="new-password" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm px-4 py-2.5" placeholder="{{ __('ui.placeholder_enter_new_password') }}">
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('ui.confirm_password') }}</label>
                <input wire:model.defer="password_confirmation" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm px-4 py-2.5" placeholder="{{ __('ui.placeholder_repeat_new_password') }}">
            </div>
        </div>

        <button type="submit" class="mt-6 w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 focus:ring-blue-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled">
            <span wire:loading.remove>{{ __('ui.reset_password_btn') }}</span>
            <span wire:loading>{{ __('ui.resetting') }}</span>
        </button>
    </form>
</div>
