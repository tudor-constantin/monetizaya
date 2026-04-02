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
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('ui.profile') }}</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('ui.manage_account_settings') }}</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <section class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-gradient-to-r from-slate-50 to-blue-50 dark:from-slate-900 dark:to-slate-800 p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('ui.profile') }}</p>
                        <h3 class="mt-2 text-2xl font-bold text-slate-900 dark:text-white">{{ __('ui.account_control_center') }}</h3>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ __('ui.account_control_center_desc') }}</p>
                    </div>
                    @if(auth()->user()->hasRole('creator') || auth()->user()->hasRole('admin'))
                        <a href="{{ route('creators.show', auth()->user()) }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100 text-white text-sm font-semibold transition-colors" wire:navigate>
                            {{ __('ui.view_public_profile') }}
                        </a>
                    @endif
                </div>
            </section>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-7 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
                        <livewire:profile.update-profile-information-form />
                    </div>

                    @if(auth()->user()->hasRole('creator') || auth()->user()->hasRole('admin'))
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 sm:p-8" id="creator-profile">
                            <livewire:profile.creator-profile-form />
                        </div>
                    @endif
                </div>

                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
                        <livewire:profile.update-password-form />
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-red-200 dark:border-red-500/30 p-6 sm:p-8">
                        <livewire:profile.delete-user-form />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
