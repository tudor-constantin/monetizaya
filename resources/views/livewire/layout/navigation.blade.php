<?php

use App\Livewire\Actions\Logout;
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
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-3 sm:gap-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0" wire:navigate>
                    <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <span class="font-bold text-lg text-gray-900 dark:text-white">{{ config('app.name') }}</span>
                </a>

                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800' }}" wire:navigate>
                        {{ __('ui.dashboard') }}
                    </a>

                    <a href="{{ route('creators.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('creators.index') || request()->routeIs('creators.show') ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800' }}" wire:navigate>
                        {{ __('ui.discover_creators') }}
                    </a>

                    @if(auth()->user()->hasRole('creator') || auth()->user()->hasRole('admin'))
                        <a href="{{ route('creator.dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('creator.*') ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800' }}" wire:navigate>
                            {{ __('ui.creator_studio') }}
                        </a>
                    @endif

                    @if(auth()->user()->hasRole('admin'))
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

                <!-- User menu -->
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
                                    @if(auth()->user()->hasRole('admin'))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-rose-100 dark:bg-rose-500/20 text-rose-700 dark:text-rose-300">{{ __('ui.admin') }}</span>
                                    @elseif(auth()->user()->hasRole('creator'))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300">{{ __('ui.creator') }}</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300">{{ __('ui.user') }}</span>
                                    @endif
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
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}" wire:navigate>{{ __('ui.dashboard') }}</a>
            <a href="{{ route('creators.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('creators.index') || request()->routeIs('creators.show') ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}" wire:navigate>{{ __('ui.discover_creators') }}</a>
            @if(auth()->user()->hasRole('creator') || auth()->user()->hasRole('admin'))
                <a href="{{ route('creator.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('creator.*') ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}" wire:navigate>{{ __('ui.creator_studio') }}</a>
            @endif
            @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.*') ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}" wire:navigate>{{ __('ui.admin') }}</a>
            @endif
        </div>
        <div class="border-t border-gray-200 dark:border-gray-800 px-4 py-3 space-y-1">
            <a href="{{ route('profile') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400" wire:navigate>{{ __('ui.profile') }}</a>
            <button wire:click="logout" class="block w-full text-left px-3 py-2 rounded-lg text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">{{ __('ui.log_out') }}</button>
        </div>
    </div>
</nav>
