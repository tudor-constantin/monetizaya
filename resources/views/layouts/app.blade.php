<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-['Manrope'] antialiased bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-white">
    <div class="min-h-screen flex flex-col">
        <livewire:layout.navigation />

        @php
            $segments = request()->segments();
            $route = request()->route();
            $postParam = $route?->parameter('post');
            $resolvedPost = null;

            if ($postParam instanceof \App\Models\Post) {
                $resolvedPost = $postParam->relationLoaded('author') ? $postParam : $postParam->loadMissing('author');
            } elseif (is_numeric($postParam)) {
                $resolvedPost = \App\Models\Post::query()->with('author')->find((int) $postParam);
            }

            $postShowUrl = null;

            if ($resolvedPost instanceof \App\Models\Post && $resolvedPost->author) {
                $postShowUrl = route('creators.posts.show', [
                    'user' => $resolvedPost->author->slug,
                    'post' => $resolvedPost->slug,
                ]);
            }

            $breadcrumbs = collect($segments)->map(function (string $segment, int $index) use ($segments, $resolvedPost, $postShowUrl) {
                $isPostIdentifier = ($resolvedPost instanceof \App\Models\Post)
                    && ((string) $resolvedPost->slug === $segment)
                    && ($segments[$index - 1] ?? null) === 'posts'
                    && ($segments[$index + 1] ?? null) === 'edit';

                if ($isPostIdentifier) {
                    $label = $resolvedPost->slug;
                    $url = $postShowUrl;

                    return ['label' => $label, 'url' => $url];
                }

                $label = match ($segment) {
                    'dashboard' => __('ui.dashboard'),
                    'profile' => __('ui.profile'),
                    'creator' => __('ui.creator_studio'),
                    'posts' => __('ui.posts'),
                    'create' => 'Create',
                    'edit' => __('ui.edit'),
                    'resources' => __('ui.resources'),
                    'courses' => __('ui.courses'),
                    'analytics' => __('ui.analytics_feature'),
                    'admin' => __('ui.admin'),
                    'users' => __('ui.users'),
                    'settings' => __('ui.settings'),
                    'creators' => __('ui.discover_creators'),
                    default => ucfirst(str_replace('-', ' ', $segment)),
                };

                $isLast = $index === count($segments) - 1;

                if ($isLast) {
                    $url = null;
                } elseif ($segment === 'creator') {
                    $url = route('creator.dashboard');
                } elseif ($segment === 'admin') {
                    $url = route('admin.dashboard');
                } elseif ($segment === 'dashboard' && $index === 0) {
                    $url = route('dashboard');
                } elseif ($segment === 'profile' && $index === 0) {
                    $url = route('profile');
                } elseif ($segment === 'creators' && $index === 0) {
                    $url = route('creators.index');
                } elseif ($segment === 'posts' && ($segments[$index - 1] ?? null) === 'creator') {
                    $url = route('creator.posts.index');
                } elseif ($segment === 'resources' && ($segments[$index - 1] ?? null) === 'creator') {
                    $url = route('creator.resources.index');
                } elseif ($segment === 'courses' && ($segments[$index - 1] ?? null) === 'creator') {
                    $url = route('creator.courses.index');
                } elseif ($segment === 'analytics' && ($segments[$index - 1] ?? null) === 'creator') {
                    $url = route('creator.analytics');
                } elseif ($segment === 'users' && ($segments[$index - 1] ?? null) === 'admin') {
                    $url = route('admin.users');
                } elseif ($segment === 'settings' && ($segments[$index - 1] ?? null) === 'admin') {
                    $url = route('admin.settings');
                } else {
                    $url = url('/'.implode('/', array_slice($segments, 0, $index + 1)));
                }

                return ['label' => $label, 'url' => $url];
            });
        @endphp

        <header class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <nav aria-label="Breadcrumb">
                    <ol class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                        @foreach ($breadcrumbs as $crumb)
                            <li class="inline-flex items-center gap-2">
                                @if ($crumb['url'])
                                    <a href="{{ $crumb['url'] }}" class="hover:text-gray-900 dark:hover:text-white transition-colors" wire:navigate>{{ $crumb['label'] }}</a>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                @else
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $crumb['label'] }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </nav>
            </div>
        </header>

        <main class="flex-1">
            {{ $slot }}
        </main>

        <footer class="border-t border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 py-6 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                    &copy; {{ date('Y') }} {{ config('app.name') }}
                </p>
            </div>
        </footer>
    </div>

    <x-toasts />
</body>
</html>
