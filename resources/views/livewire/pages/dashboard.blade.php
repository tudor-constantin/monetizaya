<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    //
}; ?>

<div>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('ui.dashboard') }}</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('ui.welcome_back_name', ['name' => auth()->user()->name]) }}</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-500/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('ui.logged_in') }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ui.ready_to_manage') }}</p>
                    </div>
                </div>

                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @if(auth()->user()->hasRole('creator') || auth()->user()->hasRole('admin'))
                        <a href="{{ route('creator.dashboard') }}" class="bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600 p-6 hover:border-blue-300 dark:hover:border-blue-500 hover:shadow-sm transition-all" wire:navigate>
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-1">{{ __('ui.creator_studio') }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ui.manage_content_earnings') }}</p>
                        </a>
                    @endif

                    @if(auth()->user()->hasRole('admin'))
                        <a href="{{ route('admin.dashboard') }}" class="bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600 p-6 hover:border-blue-300 dark:hover:border-blue-500 hover:shadow-sm transition-all" wire:navigate>
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-1">{{ __('ui.admin_panel') }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ui.manage_platform') }}</p>
                        </a>
                    @endif

                    <a href="{{ route('profile') }}" class="bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600 p-6 hover:border-blue-300 dark:hover:border-blue-500 hover:shadow-sm transition-all" wire:navigate>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-1">{{ __('ui.profile_settings') }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ui.update_account_details') }}</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
