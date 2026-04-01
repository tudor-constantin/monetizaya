<x-app-layout>
    <x-slot name="header">
        <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('home') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">{{ __('ui.home') }}</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-900 dark:text-white font-medium">{{ $creator->name }}</span>
        </nav>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Creator Hero -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
                <!-- Cover -->
                <div class="h-32 sm:h-48 bg-gradient-to-r from-blue-500 to-blue-700 relative">
                    @if($creator->cover_image)
                        <img src="{{ $creator->cover_image }}" alt="" class="absolute inset-0 w-full h-full object-cover">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                </div>

                <div class="px-6 sm:px-8 pb-8 -mt-12 relative">
                    <div class="flex flex-col sm:flex-row sm:items-end gap-4 mb-6">
                        <!-- Avatar -->
                        <div class="w-24 h-24 rounded-xl bg-white dark:bg-gray-800 border-4 border-white dark:border-gray-800 shadow-lg flex items-center justify-center text-3xl font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10">
                            {{ strtoupper(substr($creator->name, 0, 1)) }}
                        </div>

                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $creator->name }}</h1>
                            @if($creator->bio)
                                <p class="mt-1 text-gray-600 dark:text-gray-400 max-w-2xl">{{ $creator->bio }}</p>
                            @endif
                        </div>

                        @auth
                            @if(auth()->id() !== $creator->id)
                                <button class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors shrink-0">
                                    {{ __('ui.subscribe') }} €{{ number_format($creator->getActiveSubscriptionPrice(), 2) }}{{ __('ui.per_month') }}
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors shrink-0">
                                {{ __('ui.subscribe') }} €{{ number_format($creator->getActiveSubscriptionPrice(), 2) }}{{ __('ui.per_month') }}
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">{{ __('ui.latest_posts') }}</h2>

                @if($posts->count() > 0)
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($posts as $post)
                            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 hover:border-blue-300 dark:hover:border-blue-500 hover:shadow-sm transition-all">
                                <div class="flex items-center gap-2 mb-3">
                                    @if($post->is_premium)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-500/10 text-purple-800 dark:text-purple-400">{{ __('ui.premium') }}</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-500/10 text-blue-800 dark:text-blue-400">{{ __('ui.free') }}</span>
                                    @endif
                                </div>
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-2">{{ $post->title }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3 mb-4">{{ Str::limit(strip_tags($post->body), 120) }}</p>
                                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                    <span>{{ $post->published_at?->format('M d, Y') ?? __('ui.draft') }}</span>
                                    <span>{{ number_format($post->views) }} {{ __('ui.views') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        <h3 class="mt-4 text-sm font-medium text-gray-900 dark:text-white">{{ __('ui.no_posts_yet') }}</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('ui.no_creator_posts') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
