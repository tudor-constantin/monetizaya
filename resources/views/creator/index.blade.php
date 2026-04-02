<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('ui.discover_creators') }}</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('ui.explore_creator_profiles') }}</p>
            </div>

            @if ($creators->count() > 0)
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($creators as $creator)
                        <div class="group ui-card-hover-md relative overflow-hidden bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                            <div class="pointer-events-none absolute -top-8 -right-8 w-24 h-24 rounded-full bg-blue-100/80 dark:bg-blue-500/10 blur-2xl"></div>
                            <div class="-mx-6 -mt-6 mb-4 h-24 bg-gradient-to-r from-blue-800 to-blue-600 relative overflow-hidden">
                                @if($creator->cover_image_url)
                                    <img src="{{ $creator->cover_image_url }}" alt="" class="absolute inset-0 w-full h-full object-cover">
                                @endif
                            </div>

                            <div class="relative flex items-center gap-3">
                                @if($creator->avatar_url)
                                    <img src="{{ $creator->avatar_url }}" alt="{{ $creator->name }}" class="w-11 h-11 rounded-xl object-cover border border-white dark:border-slate-700 bg-white dark:bg-slate-900">
                                @else
                                    <div class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-500/10 flex items-center justify-center text-blue-700 dark:text-blue-300 font-semibold">
                                        {{ strtoupper(substr($creator->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $creator->name }}</p>
                                    @if($creator->tagline)
                                        <p class="text-xs text-blue-700 dark:text-blue-300 truncate">{{ $creator->tagline }}</p>
                                    @endif
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $creator->published_posts_count }} {{ __('ui.posts') }}</p>
                                </div>
                            </div>

                            @if ($creator->bio)
                                <p class="mt-4 text-sm text-gray-600 dark:text-gray-400 line-clamp-3">{{ $creator->bio }}</p>
                            @endif

                            <a href="{{ route('creators.show', $creator) }}" class="mt-5 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors" wire:navigate>
                                {{ __('ui.view_profile') }}
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-12 text-center">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ __('ui.no_creators_available') }}</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('ui.check_back_soon') }}</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
