<?php

use App\Livewire\Actions\Logout;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component
{
    #[On('profile-updated')]
    public function refreshNavigation(): void
    {
        // Force re-render so latest user data appears instantly in navbar.
    }

    public function logout(Logout $logout): void
    {
        $logout([
            'type' => 'success',
            'message' => __('ui.logged_out_successfully'),
        ]);

        $this->redirect('/', navigate: true);
    }

    public function switchPanel(string $panel): void
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user || ! $user->hasRole('admin') || ! in_array($panel, ['creator', 'admin'], true)) {
            return;
        }

        session(['panel_mode' => $panel]);

        $this->redirectRoute($panel === 'creator' ? 'creator.dashboard' : 'admin.dashboard', navigate: true);
    }
}; ?>

@php
    $user = auth()->user();
    $isAuthenticated = (bool) $user;
    $isCreator = $user?->hasRole('creator') ?? false;
    $isAdmin = $user?->hasRole('admin') ?? false;
    $panelMode = $isAdmin
        ? (request()->routeIs('admin.*')
            ? 'admin'
            : (request()->routeIs('creator.*')
                ? 'creator'
                : session('panel_mode', 'admin')))
        : null;
    $showCreatorLink = $isCreator && ! $isAdmin;
    $showAdminLink = false;
@endphp

<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
    <div class="ui-shell">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-3 sm:gap-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0" wire:navigate>
                    <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <span class="font-bold text-lg text-gray-900 dark:text-white">{{ config('app.name') }}</span>
                </a>

                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800' }}" wire:navigate>
                        {{ __('ui.home') }}
                    </a>

                    @if($isAuthenticated)
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800' }}" wire:navigate>
                            {{ __('ui.dashboard') }}
                        </a>
                    @endif

                    <a href="{{ route('creators.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('creators.index') || request()->routeIs('creators.show') ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800' }}" wire:navigate>
                        {{ __('ui.discover_creators') }}
                    </a>

                    @if($isAdmin)
                        <div class="ml-1 inline-flex items-center rounded-lg border border-gray-200 dark:border-gray-700 p-1">
                            <button type="button" wire:click="switchPanel('creator')" class="px-2.5 py-1.5 rounded-md transition-colors {{ $panelMode === 'creator' ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}" title="{{ __('ui.creator_studio') }}" aria-label="{{ __('ui.creator_studio') }}">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </button>
                            <button type="button" wire:click="switchPanel('admin')" class="px-2.5 py-1.5 rounded-md transition-colors {{ $panelMode === 'admin' ? 'bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}" title="{{ __('ui.admin_panel') }}" aria-label="{{ __('ui.admin_panel') }}">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.493 0 4.835.654 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </button>
                        </div>
                    @endif

                    @if($showCreatorLink)
                        <a href="{{ route('creator.dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('creator.*') ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800' }}" wire:navigate>
                            {{ __('ui.creator_studio') }}
                        </a>
                    @endif

                    @if($showAdminLink)
                        <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.*') ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800' }}" wire:navigate>
                            {{ __('ui.admin') }}
                        </a>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-3">
                <!-- Dark mode toggle -->
                <button onclick="window.toggleDarkMode()" class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" title="{{ __('ui.toggle_dark_mode') }}">
                    <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>

                @if($isAuthenticated)
                <div class="hidden sm:flex items-center gap-3">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                @if(auth()->user()->avatar_url)
                                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover border border-gray-200 dark:border-gray-700 bg-white dark:bg-slate-900">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-semibold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="flex items-center gap-1.5 max-w-[180px]">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate">{{ auth()->user()->name }}</span>
                                </div>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <x-dropdown-link :href="route('profile')" wire:navigate>
                                {{ __('ui.profile') }}
                            </x-dropdown-link>
                            <button wire:click="logout" class="w-full text-start hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <x-dropdown-link>
                                    {{ __('ui.log_out') }}
                                </x-dropdown-link>
                            </button>
                        </x-slot>
                    </x-dropdown>
                </div>
                @else
                <div class="hidden sm:flex items-center gap-2">
                    <a href="{{ route('login') }}" class="inline-flex items-center rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white" wire:navigate>
                        {{ __('ui.log_in') }}
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-flex min-h-10 items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-blue-700" wire:navigate>
                            {{ __('ui.get_started') }}
                        </a>
                    @endif
                </div>
                @endif

                <!-- Mobile menu button -->
                <button @click="open = ! open" class="md:hidden p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <svg class="h-5 w-5" :class="{'hidden': open, 'block': ! open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg class="h-5 w-5" :class="{'hidden': ! open, 'block': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="open" x-transition class="md:hidden border-t border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('home') ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}" wire:navigate>{{ __('ui.home') }}</a>
            @if($isAuthenticated)
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}" wire:navigate>{{ __('ui.dashboard') }}</a>
            @endif
            <a href="{{ route('creators.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('creators.index') || request()->routeIs('creators.show') ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}" wire:navigate>{{ __('ui.discover_creators') }}</a>

            @if($isAdmin)
                <div class="mt-3 inline-flex items-center rounded-lg border border-gray-200 dark:border-gray-700 p-1">
                    <button type="button" wire:click="switchPanel('creator')" class="px-3 py-2 rounded-md text-xs font-semibold {{ $panelMode === 'creator' ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-300' : 'text-gray-600 dark:text-gray-400' }}" title="{{ __('ui.creator_studio') }}" aria-label="{{ __('ui.creator_studio') }}">
                        C
                    </button>
                    <button type="button" wire:click="switchPanel('admin')" class="px-3 py-2 rounded-md text-xs font-semibold {{ $panelMode === 'admin' ? 'bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-300' : 'text-gray-600 dark:text-gray-400' }}" title="{{ __('ui.admin_panel') }}" aria-label="{{ __('ui.admin_panel') }}">
                        A
                    </button>
                </div>
            @endif

            @if($showCreatorLink)
                <a href="{{ route('creator.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('creator.*') ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}" wire:navigate>{{ __('ui.creator_studio') }}</a>
            @endif
            @if($showAdminLink)
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.*') ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}" wire:navigate>{{ __('ui.admin') }}</a>
            @endif
        </div>
        @if($isAuthenticated)
            <div class="border-t border-gray-200 dark:border-gray-800 px-4 py-3 space-y-1">
                <a href="{{ route('profile') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400" wire:navigate>{{ __('ui.profile') }}</a>
                <button wire:click="logout" class="block w-full text-left px-3 py-2 rounded-lg text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">{{ __('ui.log_out') }}</button>
            </div>
        @else
            <div class="border-t border-gray-200 dark:border-gray-800 px-4 py-3 space-y-1">
                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400" wire:navigate>{{ __('ui.log_in') }}</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-blue-700 dark:text-blue-300" wire:navigate>{{ __('ui.get_started') }}</a>
                @endif
            </div>
        @endif
    </div>
</nav>
