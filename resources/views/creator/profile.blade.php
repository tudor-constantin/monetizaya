<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <section class="relative rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900">
                <div class="h-40 sm:h-56 bg-gradient-to-r from-blue-900 via-blue-800 to-blue-600 relative rounded-t-3xl overflow-hidden">
                    @if($creator->cover_image_url)
                        <img src="{{ $creator->cover_image_url }}" alt="" class="absolute inset-0 w-full h-full object-cover">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/20 to-transparent"></div>
                </div>

                <div class="relative z-20 px-6 sm:px-8 pb-8 -mt-14 sm:-mt-14 lg:mt-0 lg:pt-6">
                    <div class="flex flex-col lg:flex-row lg:items-start gap-6">
                        <div class="shrink-0 relative z-30">
                            @if($creator->avatar_url)
                                <img src="{{ $creator->avatar_url }}" alt="{{ $creator->name }}" class="w-24 h-24 rounded-2xl shadow-lg object-cover bg-white dark:bg-slate-900">
                            @else
                                <div class="w-24 h-24 rounded-2xl shadow-lg bg-blue-100 dark:bg-blue-500/10 flex items-center justify-center text-3xl font-bold text-blue-700 dark:text-blue-300">
                                    {{ strtoupper(substr($creator->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <div class="flex-1">
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">{{ $creator->name }}</h1>
                            @if($creator->tagline)
                                <p class="mt-1 text-sm font-medium text-blue-700 dark:text-blue-300">{{ $creator->tagline }}</p>
                            @endif
                            <p class="mt-2 text-slate-600 dark:text-slate-300 max-w-3xl">{{ $creator->bio ?: __('ui.creator_default_bio') }}</p>
                        </div>

                        @php($social = $creator->social_links ?? [])
                        @auth
                            <div class="shrink-0 w-full lg:w-auto">
                                @if(auth()->id() !== $creator->id)
                                    <form action="{{ route('creators.subscribe', $creator) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="ui-btn-success rounded-xl px-5 py-3">
                                            {{ __('ui.subscribe') }} €{{ number_format($creator->getActiveSubscriptionPrice(), 2) }}{{ __('ui.per_month') }}
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('profile') }}#creator-profile" class="inline-flex items-center px-5 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-100 text-white dark:text-slate-900 text-sm font-semibold transition-colors" wire:navigate>
                                        {{ __('ui.customize_profile') }}
                                    </a>
                                @endif

                                @if(!empty($social))
                                    <div class="mt-3 flex flex-wrap gap-2 lg:justify-end">
                                        @foreach($social as $label => $url)
                                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors" title="{{ __('ui.social_'.$label) !== 'ui.social_'.$label ? __('ui.social_'.$label) : ucfirst($label) }}" aria-label="{{ __('ui.social_'.$label) !== 'ui.social_'.$label ? __('ui.social_'.$label) : ucfirst($label) }}">
                                                @if($label === 'twitter')
                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2H21l-6.64 7.59L22.2 22h-6.13l-4.8-6.28L5.8 22H3l7.1-8.12L2 2h6.2l4.33 5.7L18.244 2z"/></svg>
                                                @elseif($label === 'instagram')
                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="18" height="18" rx="5" ry="5" stroke-width="2"/><circle cx="12" cy="12" r="4" stroke-width="2"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                                                @elseif($label === 'youtube')
                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M21.58 7.19a2.99 2.99 0 0 0-2.1-2.12C17.64 4.5 12 4.5 12 4.5s-5.64 0-7.48.57a2.99 2.99 0 0 0-2.1 2.12A31.2 31.2 0 0 0 1.92 12c0 1.63.18 3.24.5 4.81a2.99 2.99 0 0 0 2.1 2.12c1.84.57 7.48.57 7.48.57s5.64 0 7.48-.57a2.99 2.99 0 0 0 2.1-2.12c.32-1.57.5-3.18.5-4.81 0-1.63-.18-3.24-.5-4.81ZM10 15.5v-7l6 3.5-6 3.5Z"/></svg>
                                                @else
                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 010 5.656l-3 3a4 4 0 01-5.656-5.656l1.5-1.5m4.656-1.328a4 4 0 010-5.656l3-3a4 4 0 115.656 5.656l-1.5 1.5"/></svg>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="shrink-0 w-full lg:w-auto">
                                <a href="{{ route('login') }}" class="ui-btn-success rounded-xl px-5 py-3" wire:navigate>
                                    {{ __('ui.subscribe') }} €{{ number_format($creator->getActiveSubscriptionPrice(), 2) }}{{ __('ui.per_month') }}
                                </a>

                                @if(!empty($social))
                                    <div class="mt-3 flex flex-wrap gap-2 lg:justify-end">
                                        @foreach($social as $label => $url)
                                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors" title="{{ __('ui.social_'.$label) !== 'ui.social_'.$label ? __('ui.social_'.$label) : ucfirst($label) }}" aria-label="{{ __('ui.social_'.$label) !== 'ui.social_'.$label ? __('ui.social_'.$label) : ucfirst($label) }}">
                                                @if($label === 'twitter')
                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2H21l-6.64 7.59L22.2 22h-6.13l-4.8-6.28L5.8 22H3l7.1-8.12L2 2h6.2l4.33 5.7L18.244 2z"/></svg>
                                                @elseif($label === 'instagram')
                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="18" height="18" rx="5" ry="5" stroke-width="2"/><circle cx="12" cy="12" r="4" stroke-width="2"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                                                @elseif($label === 'youtube')
                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M21.58 7.19a2.99 2.99 0 0 0-2.1-2.12C17.64 4.5 12 4.5 12 4.5s-5.64 0-7.48.57a2.99 2.99 0 0 0-2.1 2.12A31.2 31.2 0 0 0 1.92 12c0 1.63.18 3.24.5 4.81a2.99 2.99 0 0 0 2.1 2.12c1.84.57 7.48.57 7.48.57s5.64 0 7.48-.57a2.99 2.99 0 0 0 2.1-2.12c.32-1.57.5-3.18.5-4.81 0-1.63-.18-3.24-.5-4.81ZM10 15.5v-7l6 3.5-6 3.5Z"/></svg>
                                                @else
                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 010 5.656l-3 3a4 4 0 01-5.656-5.656l1.5-1.5m4.656-1.328a4 4 0 010-5.656l3-3a4 4 0 115.656 5.656l-1.5 1.5"/></svg>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endauth
                    </div>
                </div>
            </section>

            <section>
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('ui.latest_posts') }}</h2>
                    <span class="text-sm text-slate-500 dark:text-slate-400">{{ $posts->count() }} {{ __('ui.posts') }}</span>
                </div>

                @if(! $canViewPremium)
                    <div class="mb-5 rounded-xl border border-blue-200 dark:border-blue-500/30 bg-blue-50 dark:bg-blue-500/10 px-4 py-3 text-sm text-blue-900 dark:text-blue-300">
                        {{ __('ui.premium_posts_locked_hint') }}
                    </div>
                @endif

                @if($posts->count() > 0)
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($posts as $post)
                            <article class="group ui-card-hover bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 dark:hover:border-slate-600 p-5">
                                <div class="relative mb-4 h-36 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800">
                                    @if($post->cover_image)
                                        <img src="{{ Str::startsWith($post->cover_image, ['http://', 'https://']) ? $post->cover_image : asset('storage/'.$post->cover_image) }}" alt="" class="w-full h-full object-cover">
                                    @endif
                                    @if($post->is_premium)
                                        <span class="absolute left-3 bottom-3 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-900 text-white">{{ __('ui.premium') }}</span>
                                    @else
                                        <span class="absolute left-3 bottom-3 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-600 text-white">{{ __('ui.free') }}</span>
                                    @endif
                                </div>

                                <div class="flex items-center justify-end text-xs mb-3">
                                    <span class="text-slate-500 dark:text-slate-400">{{ $post->published_at?->format('M d, Y') ?? __('ui.draft') }}</span>
                                </div>

                                <h3 class="font-bold text-slate-900 dark:text-white line-clamp-2">{{ $post->title }}</h3>
                                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300 line-clamp-3">{{ $post->excerpt ?: Str::limit(strip_tags($post->body), 140) }}</p>

                                @if($post->is_premium && ! $canViewPremium)
                                    <p class="mt-2 text-xs font-medium text-blue-700 dark:text-blue-300">{{ __('ui.premium_preview_only') }}</p>
                                @endif

                                <div class="mt-4 flex items-center justify-between">
                                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ number_format($post->views) }} {{ __('ui.views') }}</span>
                                    <a href="{{ route('creators.posts.show', [$creator, $post]) }}" class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors" wire:navigate>
                                        {{ $post->is_premium && ! $canViewPremium ? __('ui.unlock_post') : __('ui.read_post') }}
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 p-12 text-center">
                        <h3 class="text-sm font-medium text-slate-900 dark:text-white">{{ __('ui.no_posts_yet') }}</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('ui.no_creator_posts') }}</p>
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
