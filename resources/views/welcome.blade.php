<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - Monetize your content</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-50 font-['Manrope'] antialiased text-slate-900 dark:bg-slate-950 dark:text-white">
    <a href="#contenido" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[60] focus:rounded-lg focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-slate-900">
        {{ __('ui.skip_to_main_content') }}
    </a>

    <livewire:layout.navigation />

    <main id="contenido">
        <section class="relative overflow-hidden border-b border-slate-200 pb-16 pt-14 sm:pt-16 lg:pb-24 lg:pt-20 dark:border-slate-800" aria-labelledby="hero-title">
            <div class="pointer-events-none absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top_right,rgba(11,87,208,0.18),transparent_56%),radial-gradient(circle_at_top_left,rgba(59,130,246,0.14),transparent_52%),linear-gradient(to_bottom,#f8fbff_0%,#f8fafc_65%)] dark:bg-[radial-gradient(circle_at_top_right,rgba(59,130,246,0.16),transparent_56%),radial-gradient(circle_at_top_left,rgba(37,99,235,0.12),transparent_52%),linear-gradient(to_bottom,#020617_0%,#020617_100%)]"></div>
            <div class="ui-shell grid gap-10 lg:grid-cols-12 lg:items-center lg:gap-12">
                <div class="lg:col-span-7">
                    <span class="mb-5 inline-flex rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-sm font-semibold text-blue-800 dark:border-blue-900/80 dark:bg-blue-900/30 dark:text-blue-200">
                        {{ __('ui.monetization_platform') }}
                    </span>
                    <h1 id="hero-title" class="text-balance text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl lg:text-6xl dark:text-white">
                        {{ __('ui.monetize_content') }}
                        <span class="block text-blue-600 dark:text-blue-400">{{ __('ui.without_middlemen') }}</span>
                    </h1>
                    <p class="mt-6 max-w-2xl text-lg leading-relaxed text-slate-700 dark:text-slate-300">
                        {{ __('ui.hero_desc') }}
                    </p>

                    <div class="mt-8 flex flex-col items-start gap-3 sm:flex-row sm:items-center">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-flex min-h-11 items-center justify-center rounded-lg bg-blue-600 px-6 py-3 text-base font-semibold text-white transition-colors hover:bg-blue-700" wire:navigate>
                                {{ __('ui.go_to_dashboard') }}
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="inline-flex min-h-11 items-center justify-center rounded-lg bg-blue-600 px-6 py-3 text-base font-semibold text-white transition-colors hover:bg-blue-700" wire:navigate>
                                {{ __('ui.start_for_free') }}
                            </a>
                            <a href="#features" class="inline-flex min-h-11 items-center justify-center rounded-lg border border-slate-300 bg-white px-6 py-3 text-base font-semibold text-slate-800 transition-colors hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800">
                                {{ __('ui.learn_more') }}
                            </a>
                        @endauth
                    </div>
                </div>

                <aside class="lg:col-span-5" aria-label="{{ __('ui.platform_highlights') }}">
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('ui.creator_pulse') }}</p>
                        <div class="mt-5 grid grid-cols-2 gap-3">
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/70">
                                <p class="text-2xl font-extrabold text-slate-900 dark:text-white">24/7</p>
                                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ __('ui.automated_subscriptions') }}</p>
                            </div>
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/70">
                                <p class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ __('ui.stripe_label') }}</p>
                                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ __('ui.secure_checkout') }}</p>
                            </div>
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/70">
                                <p class="text-2xl font-extrabold text-slate-900 dark:text-white">100%</p>
                                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ __('ui.content_ownership') }}</p>
                            </div>
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/70">
                                <p class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ __('ui.global_label') }}</p>
                                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ __('ui.audience_ready') }}</p>
                            </div>
                        </div>
                        <p class="mt-5 text-sm leading-relaxed text-slate-600 dark:text-slate-300">{{ __('ui.publish_once_sell_repeatedly') }}</p>
                    </div>
                </aside>
            </div>
        </section>

        <section id="features" class="border-y border-slate-200 bg-white py-16 dark:border-slate-800 dark:bg-slate-950" aria-labelledby="features-title">
            <div class="ui-shell">
                <div class="mb-10 max-w-2xl">
                    <h2 id="features-title" class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">{{ __('ui.everything_you_need') }}</h2>
                    <p class="mt-3 text-lg text-slate-700 dark:text-slate-300">{{ __('ui.tools_recurring_revenue') }}</p>
                </div>

                <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                    <article class="rounded-2xl border border-slate-200 bg-slate-50 p-6 transition-shadow hover:shadow-md dark:border-slate-800 dark:bg-slate-900/70 dark:hover:border-slate-700">
                        <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-lg bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300" aria-hidden="true">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('ui.premium_posts') }}</h3>
                        <p class="mt-2 text-slate-700 dark:text-slate-300">{{ __('ui.premium_posts_desc') }}</p>
                    </article>

                    <article class="rounded-2xl border border-slate-200 bg-slate-50 p-6 transition-shadow hover:shadow-md dark:border-slate-800 dark:bg-slate-900/70 dark:hover:border-slate-700">
                        <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-lg bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300" aria-hidden="true">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('ui.downloadable_resources') }}</h3>
                        <p class="mt-2 text-slate-700 dark:text-slate-300">{{ __('ui.downloadable_resources_desc') }}</p>
                    </article>

                    <article class="rounded-2xl border border-slate-200 bg-slate-50 p-6 transition-shadow hover:shadow-md dark:border-slate-800 dark:bg-slate-900/70 dark:hover:border-slate-700">
                        <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-lg bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300" aria-hidden="true">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('ui.courses_feature') }}</h3>
                        <p class="mt-2 text-slate-700 dark:text-slate-300">{{ __('ui.courses_feature_desc') }}</p>
                    </article>

                    <article class="rounded-2xl border border-slate-200 bg-slate-50 p-6 transition-shadow hover:shadow-md dark:border-slate-800 dark:bg-slate-900/70 dark:hover:border-slate-700">
                        <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-lg bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300" aria-hidden="true">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('ui.recurring_payments') }}</h3>
                        <p class="mt-2 text-slate-700 dark:text-slate-300">{{ __('ui.recurring_payments_desc') }}</p>
                    </article>

                    <article class="rounded-2xl border border-slate-200 bg-slate-50 p-6 transition-shadow hover:shadow-md dark:border-slate-800 dark:bg-slate-900/70 dark:hover:border-slate-700">
                        <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-lg bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300" aria-hidden="true">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('ui.analytics_feature') }}</h3>
                        <p class="mt-2 text-slate-700 dark:text-slate-300">{{ __('ui.analytics_feature_desc') }}</p>
                    </article>

                    <article class="rounded-2xl border border-slate-200 bg-slate-50 p-6 transition-shadow hover:shadow-md dark:border-slate-800 dark:bg-slate-900/70 dark:hover:border-slate-700">
                        <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-lg bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300" aria-hidden="true">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('ui.total_security') }}</h3>
                        <p class="mt-2 text-slate-700 dark:text-slate-300">{{ __('ui.total_security_desc') }}</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="py-16 bg-slate-100/80 dark:bg-slate-900/40" aria-labelledby="cta-title">
            <div class="ui-shell">
                <div class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-10 dark:border-slate-800 dark:bg-slate-900">
                    <div class="pointer-events-none absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-blue-600 via-blue-500 to-blue-600"></div>
                    <div class="pointer-events-none absolute -right-16 -top-20 h-56 w-56 rounded-full bg-blue-100/70 blur-3xl dark:bg-blue-900/20"></div>

                    <div class="relative grid gap-8 lg:grid-cols-12 lg:items-center">
                        <div class="lg:col-span-7">
                            <span class="inline-flex rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-sm font-semibold text-blue-800 dark:border-blue-900/70 dark:bg-blue-900/30 dark:text-blue-200">
                                {{ __('ui.monetization_platform') }}
                            </span>
                            <h2 id="cta-title" class="mt-5 max-w-3xl text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl dark:text-white">{{ __('ui.ready_to_monetize') }}</h2>
                            <p class="mt-4 max-w-2xl text-lg leading-relaxed text-slate-700 dark:text-slate-300">{{ __('ui.join_today') }}</p>

                            <div class="mt-8 flex flex-col items-start gap-3 sm:flex-row sm:items-center">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="inline-flex min-h-11 items-center rounded-lg bg-blue-600 px-6 py-3 text-base font-semibold text-white transition-colors hover:bg-blue-700" wire:navigate>
                                        {{ __('ui.go_to_dashboard') }}
                                    </a>
                                @else
                                    <a href="{{ route('register') }}" class="inline-flex min-h-11 items-center rounded-lg bg-blue-600 px-6 py-3 text-base font-semibold text-white transition-colors hover:bg-blue-700" wire:navigate>
                                        {{ __('ui.create_free_account') }}
                                    </a>
                                @endauth

                                <a href="{{ route('creators.index') }}" class="inline-flex min-h-11 items-center rounded-lg border border-slate-300 bg-white px-6 py-3 text-base font-semibold text-slate-800 transition-colors hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800" wire:navigate>
                                    {{ __('ui.discover_creators') }}
                                </a>
                            </div>
                        </div>

                        <aside class="lg:col-span-5" aria-label="{{ __('ui.platform_highlights') }}">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-800/60">
                                <h3 class="text-base font-semibold text-slate-900 dark:text-white">{{ __('ui.everything_you_need') }}</h3>
                                <ul class="mt-4 space-y-3 text-sm text-slate-700 dark:text-slate-300">
                                    <li class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full bg-blue-600" aria-hidden="true"></span>
                                        {{ __('ui.premium_posts') }}
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full bg-blue-600" aria-hidden="true"></span>
                                        {{ __('ui.downloadable_resources') }}
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full bg-blue-600" aria-hidden="true"></span>
                                        {{ __('ui.recurring_payments') }}
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full bg-blue-600" aria-hidden="true"></span>
                                        {{ __('ui.analytics_feature') }}
                                    </li>
                                </ul>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 bg-white dark:bg-slate-950" aria-labelledby="creators-title">
            <div class="ui-shell">
                <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 id="creators-title" class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">{{ __('ui.discover_creators') }}</h2>
                        <p class="mt-2 text-lg text-slate-700 dark:text-slate-300">{{ __('ui.explore_creator_profiles') }}</p>
                    </div>
                    <a href="{{ route('creators.index') }}" class="inline-flex min-h-10 items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-800 transition-colors hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800" wire:navigate>
                        {{ __('ui.see_all_creators') }}
                    </a>
                </div>

                @if ($creators->count() > 0)
                    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($creators as $creator)
                            <article class="ui-card-hover-md rounded-2xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-slate-700">
                                <div class="mb-4 h-24 overflow-hidden rounded-xl bg-gradient-to-r from-blue-800 via-blue-700 to-blue-600">
                                    @if ($creator->cover_image_url)
                                        <img src="{{ $creator->cover_image_url }}" alt="" class="h-full w-full object-cover">
                                    @endif
                                </div>

                                <div class="flex items-center gap-3">
                                    @if ($creator->avatar_url)
                                        <img src="{{ $creator->avatar_url }}" alt="{{ $creator->name }}" class="h-11 w-11 rounded-xl border border-white bg-white object-cover dark:border-slate-700 dark:bg-slate-900">
                                    @else
                                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-base font-bold text-blue-700 dark:bg-blue-900/50 dark:text-blue-300" aria-hidden="true">
                                            {{ strtoupper(substr($creator->name, 0, 1)) }}
                                        </div>
                                    @endif

                                    <div class="min-w-0">
                                        <h3 class="truncate text-base font-semibold text-slate-900 dark:text-white">{{ $creator->name }}</h3>
                                        @if ($creator->tagline)
                                            <p class="truncate text-sm text-slate-600 dark:text-slate-300">{{ $creator->tagline }}</p>
                                        @endif
                                    </div>
                                </div>

                                @if ($creator->bio)
                                    <p class="mt-4 line-clamp-3 text-sm leading-relaxed text-slate-700 dark:text-slate-300">{{ $creator->bio }}</p>
                                @endif

                                <div class="mt-5 flex items-center justify-between">
                                    <p class="text-sm text-slate-600 dark:text-slate-300">
                                        <span class="font-semibold text-slate-900 dark:text-white">{{ $creator->published_posts_count }}</span> {{ __('ui.posts') }}
                                    </p>
                                    <a href="{{ route('creators.show', $creator) }}" class="inline-flex min-h-10 items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-blue-700" wire:navigate>
                                        {{ __('ui.view_profile') }}
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center dark:border-slate-700 dark:bg-slate-900">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('ui.no_creators_available') }}</h3>
                        <p class="mt-2 text-slate-600 dark:text-slate-300">{{ __('ui.check_back_soon') }}</p>
                    </div>
                @endif
            </div>
        </section>
    </main>

    <footer class="border-t border-slate-200 py-8 dark:border-slate-800">
        <div class="ui-shell">
            <p class="text-center text-sm text-slate-600 dark:text-slate-400">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
        </div>
    </footer>

    <x-toasts />

    @livewireScripts
</body>
</html>
