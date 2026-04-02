<x-app-layout>
    <x-slot name="header">
        <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('home') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors" wire:navigate>{{ __('ui.home') }}</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('creators.index') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors" wire:navigate>{{ __('ui.creator') }}</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('creators.show', $creator) }}" class="hover:text-gray-900 dark:hover:text-white transition-colors" wire:navigate>{{ $creator->name }}</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-900 dark:text-white font-medium line-clamp-1">{{ $post->title }}</span>
        </nav>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <article class="lg:col-span-8 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                    @if($post->cover_image)
                        <div class="relative">
                            <img src="{{ Str::startsWith($post->cover_image, ['http://', 'https://']) ? $post->cover_image : asset('storage/'.$post->cover_image) }}" alt="" class="w-full h-56 sm:h-72 object-cover bg-slate-100 dark:bg-slate-800">
                            @if($post->is_premium)
                                <span class="absolute left-4 bottom-4 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-900 text-white">{{ __('ui.premium') }}</span>
                            @else
                                <span class="absolute left-4 bottom-4 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-600 text-white">{{ __('ui.free') }}</span>
                            @endif
                        </div>
                    @endif

                    <div class="p-6 sm:p-8">
                        <div class="flex items-center gap-2 mb-3">
                            @if(! $post->cover_image)
                                @if($post->is_premium)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200">{{ __('ui.premium') }}</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-500/10 text-blue-800 dark:text-blue-300">{{ __('ui.free') }}</span>
                                @endif
                            @endif
                            <span class="text-xs text-slate-500 dark:text-slate-400">{{ $post->published_at?->format('M d, Y') }}</span>
                        </div>

                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">{{ $post->title }}</h1>
                        @if($post->excerpt)
                            <p class="mt-2 text-sm sm:text-base text-slate-600 dark:text-slate-300">{{ $post->excerpt }}</p>
                        @endif

                        <div class="mt-6 prose dark:prose-invert max-w-none text-slate-800 dark:text-slate-200">
                            {!! nl2br(e($displayBody)) !!}
                        </div>

                        @if($isLocked)
                            <div class="mt-6 rounded-xl border border-blue-200 dark:border-blue-500/30 bg-blue-50 dark:bg-blue-500/10 p-5">
                                <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-200">{{ __('ui.premium_article_locked_title') }}</h3>
                                <p class="mt-1 text-sm text-blue-800 dark:text-blue-300">{{ __('ui.premium_article_locked_desc') }}</p>
                                <div class="mt-4">
                                    @auth
                                        <form action="{{ route('creators.subscribe', $creator) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="ui-btn-success">
                                                {{ __('ui.subscribe') }} €{{ number_format($creator->getActiveSubscriptionPrice(), 2) }}{{ __('ui.per_month') }}
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="ui-btn-primary" wire:navigate>
                                            {{ __('ui.log_in_to_continue') }}
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        @endif
                    </div>
                </article>

                <aside class="lg:col-span-4 space-y-4">
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 p-5">
                        <div class="flex items-center gap-3">
                            @if($creator->avatar_url)
                                <img src="{{ $creator->avatar_url }}" alt="{{ $creator->name }}" class="w-12 h-12 rounded-xl object-cover border border-gray-200 dark:border-gray-700 bg-white dark:bg-slate-900">
                            @else
                                <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-300 font-bold flex items-center justify-center">{{ strtoupper(substr($creator->name, 0, 1)) }}</div>
                            @endif
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $creator->name }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('ui.member_since', ['date' => $creator->created_at?->format('M Y')]) }}</p>
                            </div>
                        </div>

                        @if($creator->tagline)
                            <p class="mt-3 text-sm font-medium text-blue-700 dark:text-blue-300">{{ $creator->tagline }}</p>
                        @endif

                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $creator->bio ?: __('ui.creator_default_bio') }}</p>

                    </div>

                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 p-5">
                        <div class="flex items-center justify-between gap-2">
                            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('ui.latest_posts') }}</h3>
                            <a href="{{ route('creators.show', $creator) }}" class="inline-flex items-center px-2.5 py-1 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition-colors" wire:navigate>{{ __('ui.creator_posts') }}</a>
                        </div>
                        <div class="mt-3 space-y-2">
                            @forelse($recentPosts as $recentPost)
                                <a href="{{ route('creators.posts.show', [$creator, $recentPost]) }}" class="block rounded-lg border border-slate-200 dark:border-slate-700 px-3 py-2 hover:border-blue-300 dark:hover:border-blue-500 transition-colors" wire:navigate>
                                    <p class="text-sm font-medium text-slate-900 dark:text-white line-clamp-2">{{ $recentPost->title }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $recentPost->published_at?->format('M d, Y') }}</p>
                                </a>
                            @empty
                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('ui.no_creator_posts') }}</p>
                            @endforelse
                        </div>

                        <div class="mt-5 pt-4 border-t border-slate-200 dark:border-slate-700">
                            <p class="text-xs font-semibold text-slate-600 dark:text-slate-300 mb-2">{{ __('ui.share_article') }}</p>
                            @php
                                $shareUrl = urlencode(request()->fullUrl());
                                $shareTitle = urlencode($post->title);
                            @endphp
                            <div class="flex items-center gap-2">
                                <a href="https://x.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition-colors" aria-label="Share on X">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2H21l-6.64 7.59L22.2 22h-6.13l-4.8-6.28L5.8 22H3l7.1-8.12L2 2h6.2l4.33 5.7L18.244 2z"/></svg>
                                </a>
                                <a href="https://www.instagram.com/?url={{ $shareUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition-colors" aria-label="Share on Instagram">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="18" height="18" rx="5" ry="5" stroke-width="2"/><circle cx="12" cy="12" r="4" stroke-width="2"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                                </a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition-colors" aria-label="Share on LinkedIn">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6.94 8.5A1.75 1.75 0 1 1 6.94 5a1.75 1.75 0 0 1 0 3.5ZM5.5 9.75h2.88V19H5.5V9.75Zm5.04 0h2.76v1.26h.04c.38-.73 1.32-1.5 2.72-1.5 2.92 0 3.46 1.92 3.46 4.42V19h-2.88v-4.52c0-1.08-.02-2.46-1.5-2.46-1.5 0-1.73 1.17-1.73 2.39V19h-2.88V9.75Z"/></svg>
                                </a>
                                <a href="mailto:?subject={{ $shareTitle }}&body={{ $shareUrl }}" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition-colors" aria-label="Share by email">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M2.25 6.75A2.25 2.25 0 0 1 4.5 4.5h15a2.25 2.25 0 0 1 2.25 2.25v10.5A2.25 2.25 0 0 1 19.5 19.5h-15a2.25 2.25 0 0 1-2.25-2.25V6.75Zm1.61-.75 7.53 5.65a1.5 1.5 0 0 0 1.8 0L20.14 6H3.86Z"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
