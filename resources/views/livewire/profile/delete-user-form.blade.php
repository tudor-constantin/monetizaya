<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    public function deleteUser(Logout $logout): void
    {
        $this->validate(['password' => ['required', 'string', 'current_password']]);
        tap(Auth::user(), $logout(...))->delete();
        $this->redirect('/', navigate: true);
    }
}; ?>

<div x-data="{ open: false }">
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-red-600 dark:text-red-400">{{ __('ui.delete_account') }}</h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('ui.delete_account_desc') }}</p>
    </div>

    <button @click="open = true" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
        {{ __('ui.delete_account') }}
    </button>

    <div x-show="open" x-transition class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false"></div>
            <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl sm:my-8 sm:w-full sm:max-w-lg">
                <form wire:submit="deleteUser" class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-500/10 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('ui.delete_account') }}</h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('ui.delete_account_confirm') }}</p>
                            <div class="mt-4">
                                <input wire:model="password" id="delete_password" type="password" class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-red-500 dark:focus:border-red-400 focus:ring-red-500 dark:focus:ring-red-400 sm:text-sm px-4 py-2.5" placeholder="{{ __('ui.placeholder_enter_password_confirm') }}">
                                <x-input-error class="mt-1.5" :messages="$errors->get('password')" />
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">{{ __('ui.delete_account') }}</button>
                        <button type="button" @click="open = false" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 sm:mt-0 sm:w-auto">{{ __('ui.cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
