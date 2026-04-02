<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('ui.creator_studio_title') }}</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('ui.creator_studio_desc') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('profile') }}#creator-profile" class="inline-flex items-center px-4 py-2 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 text-sm font-medium rounded-lg transition-colors" wire:navigate>
                    {{ __('ui.customize_profile') }}
                </a>
                <a href="{{ route('creator.posts.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors" wire:navigate>
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    {{ __('ui.new_post') }}
                </a>
            </div>
        </div>

        @if(! auth()->user()->subscription_price)
            <div class="mb-8 rounded-xl border border-blue-200 dark:border-blue-500/30 bg-blue-50 dark:bg-blue-500/10 p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <p class="text-sm text-blue-900 dark:text-blue-300">{{ __('ui.creator_set_price_prompt') }}</p>
                <a href="{{ route('profile') }}#creator-profile" class="inline-flex items-center px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition-colors" wire:navigate>{{ __('ui.customize_profile') }}</a>
            </div>
        @endif

        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('ui.monthly_earnings') }}</p>
                <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">€{{ number_format($earnings['net_earnings'] ?? 0, 2) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('ui.total_posts') }}</p>
                <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ $totalPosts }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('ui.total_views') }}</p>
                <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalViews) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('ui.gross_revenue') }}</p>
                <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">€{{ number_format($earnings['gross_earnings'] ?? 0, 2) }}</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 mb-8">
            <a href="{{ route('creator.posts.index') }}" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-sm transition-shadow" wire:navigate>
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-500/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('ui.posts') }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ui.manage_articles') }}</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('creator.resources.index') }}" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-sm transition-shadow" wire:navigate>
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-500/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('ui.resources') }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ui.downloadable_files') }}</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('creator.courses.index') }}" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-sm transition-shadow" wire:navigate>
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-500/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('ui.courses') }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ui.build_courses') }}</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('profile') }}#creator-profile" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-sm transition-shadow" wire:navigate>
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-500/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.493 0 4.835.654 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('ui.customize_profile') }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ui.quick_customize_profile') }}</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('creators.show', auth()->user()) }}" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-sm transition-shadow" wire:navigate>
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-500/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('ui.open_public_page') }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ui.view_public_profile') }}</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Transactions -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('ui.recent_transactions') }}</h2>
            </div>

            @if(count($recentTransactions) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('ui.date') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('ui.subscriber') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('ui.amount') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('ui.net') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('ui.status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($recentTransactions as $transaction)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{ $transaction['paid_at'] ? \Carbon\Carbon::parse($transaction['paid_at'])->format('M d, Y') : __('ui.na') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{ $transaction['subscriber']['name'] ?? __('ui.unknown') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">€{{ number_format($transaction['gross_amount'], 2) }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-green-600 dark:text-green-400">€{{ number_format($transaction['net_amount'], 2) }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transaction['status'] === 'completed' ? 'bg-green-100 dark:bg-green-500/10 text-green-800 dark:text-green-400' : 'bg-yellow-100 dark:bg-yellow-500/10 text-yellow-800 dark:text-yellow-400' }}">
                                            {{ __($transaction['status']) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3 class="mt-4 text-sm font-medium text-gray-900 dark:text-white">{{ __('ui.no_transactions_yet') }}</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('ui.no_transactions_desc') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
