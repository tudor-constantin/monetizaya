<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $errors = collect($e->validator->errors()->all())->join('. ');
            $this->dispatch('toast', type: 'error', message: $errors);

            throw $e;
        }

        Auth::user()->update(['password' => Hash::make($validated['password'])]);
        $this->reset('current_password', 'password', 'password_confirmation');
        $this->dispatch('toast', type: 'success', message: 'Password updated successfully.');
    }
}; ?>

<div>
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('ui.update_password') }}</h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('ui.update_password_desc') }}</p>
    </div>

    <form wire:submit="updatePassword" class="space-y-5">
        <div>
            <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('ui.current_password') }}</label>
            <input wire:model.defer="current_password" id="current_password" name="current_password" type="password" autocomplete="current-password" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm px-4 py-2.5" placeholder="{{ __('ui.placeholder_enter_current_password') }}">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('ui.new_password') }}</label>
            <input wire:model.defer="password" id="password" name="password" type="password" autocomplete="new-password" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm px-4 py-2.5" placeholder="{{ __('ui.placeholder_enter_new_password') }}">
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('ui.confirm_password') }}</label>
            <input wire:model.defer="password_confirmation" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm px-4 py-2.5" placeholder="{{ __('ui.placeholder_repeat_new_password') }}">
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ __('ui.save') }}</span>
                <span wire:loading>Saving...</span>
            </button>
        </div>
    </form>
</div>
