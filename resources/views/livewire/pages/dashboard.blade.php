<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public function requestCreatorAccess(): void
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user) {
            return;
        }

        if ($user->hasRole('creator') || $user->hasRole('admin')) {
            $this->redirectRoute('creator.dashboard', navigate: true);

            return;
        }

        if (! Schema::hasColumn('users', 'creator_requested_at')) {
            $this->dispatch('toast', type: 'error', message: __('ui.system_update_required'));

            return;
        }

        if (! $user->creator_requested_at) {
            $user->forceFill([
                'creator_requested_at' => now(),
            ])->save();

            $this->dispatch('toast', type: 'success', message: __('ui.creator_request_submitted'));

            return;
        }

        $this->dispatch('toast', type: 'info', message: __('ui.creator_request_already_submitted'));
    }
}; ?>

<div>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('ui.dashboard') }}</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('ui.welcome_back_name', ['name' => auth()->user()->name]) }}</p>
            </div>

            <section class="relative overflow-hidden rounded-3xl border border-slate-200/70 dark:border-slate-700/60 bg-gradient-to-br from-blue-50 via-blue-100 to-white dark:from-slate-900 dark:via-slate-900 dark:to-slate-800 p-6 sm:p-10">
                <div class="pointer-events-none absolute -top-16 -right-10 h-48 w-48 rounded-full bg-blue-200/50 blur-3xl dark:bg-blue-500/20"></div>
                <div class="pointer-events-none absolute -bottom-20 -left-8 h-52 w-52 rounded-full bg-blue-200/50 blur-3xl dark:bg-blue-500/20"></div>

                <div class="relative">
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-blue-700 dark:text-blue-300">{{ __('ui.app_name') }}</p>
                    <h3 class="mt-3 text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">{{ __('ui.dashboard_focus_title') }}</h3>
                    <p class="mt-2 max-w-2xl text-sm sm:text-base text-slate-600 dark:text-slate-300">{{ __('ui.dashboard_focus_desc') }}</p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('creators.index') }}" class="inline-flex items-center rounded-xl bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-100 px-4 py-2.5 text-sm font-semibold text-white dark:text-slate-900 transition-colors" wire:navigate>
                            {{ __('ui.discover_creators') }}
                        </a>
                        <a href="{{ route('profile') }}" class="inline-flex items-center rounded-xl border border-slate-300/80 dark:border-slate-600 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-white/70 dark:hover:bg-slate-800 transition-colors" wire:navigate>
                            {{ __('ui.profile_settings') }}
                        </a>
                    </div>
                </div>
            </section>

            <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                <a href="{{ route('profile') }}" class="group ui-card-hover rounded-2xl border border-slate-200 dark:border-slate-700 dark:hover:border-slate-500 bg-white/90 dark:bg-slate-900 p-5" wire:navigate>
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">{{ __('ui.account') }}</p>
                    <h4 class="mt-2 text-lg font-bold text-slate-900 dark:text-white">{{ __('ui.profile_settings') }}</h4>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ __('ui.update_account_details') }}</p>
                </a>

                <a href="{{ route('creators.index') }}" class="group ui-card-hover rounded-2xl border border-slate-200 dark:border-slate-700 dark:hover:border-slate-500 bg-white/90 dark:bg-slate-900 p-5" wire:navigate>
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">{{ __('ui.explore') }}</p>
                    <h4 class="mt-2 text-lg font-bold text-slate-900 dark:text-white">{{ __('ui.discover_creators') }}</h4>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ __('ui.explore_creator_profiles') }}</p>
                </a>

                @if(auth()->user()->hasRole('creator') || auth()->user()->hasRole('admin'))
                    <a href="{{ route('creator.dashboard') }}" class="group ui-card-hover rounded-2xl border border-slate-200 dark:border-slate-700 dark:hover:border-slate-500 bg-white/90 dark:bg-slate-900 p-5" wire:navigate>
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">{{ __('ui.studio') }}</p>
                        <h4 class="mt-2 text-lg font-bold text-slate-900 dark:text-white">{{ __('ui.creator_studio') }}</h4>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ __('ui.manage_content_earnings') }}</p>
                    </a>
                @endif

                @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('admin.dashboard') }}" class="group ui-card-hover rounded-2xl border border-slate-200 dark:border-slate-700 dark:hover:border-slate-500 bg-white/90 dark:bg-slate-900 p-5" wire:navigate>
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">{{ __('ui.admin') }}</p>
                        <h4 class="mt-2 text-lg font-bold text-slate-900 dark:text-white">{{ __('ui.admin_panel') }}</h4>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ __('ui.manage_platform') }}</p>
                    </a>
                @endif
            </section>

            @if(! auth()->user()->hasRole('creator') && ! auth()->user()->hasRole('admin'))
                <section class="rounded-2xl border border-blue-200 dark:border-blue-500/30 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-500/10 dark:to-blue-500/5 p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h4 class="text-lg font-bold text-blue-900 dark:text-blue-200">{{ __('ui.creator_onboarding_title') }}</h4>
                            <p class="mt-1 text-sm text-blue-800 dark:text-blue-300">{{ __('ui.creator_onboarding_desc') }}</p>
                        </div>
                        <button wire:click="requestCreatorAccess" class="ui-btn-primary rounded-xl px-4 py-2.5">
                            {{ __('ui.request_creator_access') }}
                        </button>
                    </div>
                </section>
            @endif
        </div>
    </div>
</div>
