<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('ui.users') }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('ui.manage_all_users') }}</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('ui.total_users') }}</p>
                        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('ui.active') }}</p>
                        <p class="mt-1 text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['active'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-green-50 dark:bg-green-500/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('ui.creators') }}</p>
                        <p class="mt-1 text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['creators'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('ui.pending_requests') }}</p>
                        <p class="mt-1 text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['pending'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 mb-6">
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Search -->
                <div class="flex-1">
                    <label for="search-input" class="sr-only">{{ __('ui.search') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input
                            type="text"
                            id="search-input"
                            wire:model.live.debounce.300ms="search"
                            placeholder="{{ __('ui.search_users_placeholder') }}"
                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                        >
                    </div>
                </div>

                <!-- Role Filter -->
                <div class="sm:w-40">
                    <label for="role-filter" class="sr-only">{{ __('ui.role') }}</label>
                    <select
                        id="role-filter"
                        wire:model.live="roleFilter"
                        class="ui-select block w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                    >
                        <option value="">{{ __('ui.all_roles') }}</option>
                        <option value="admin">{{ __('ui.admin') }}</option>
                        <option value="creator">{{ __('ui.creator') }}</option>
                        <option value="user">{{ __('ui.user') }}</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="sm:w-40">
                    <label for="status-filter" class="sr-only">{{ __('ui.status') }}</label>
                    <select
                        id="status-filter"
                        wire:model.live="statusFilter"
                        class="ui-select block w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                    >
                        <option value="">{{ __('ui.all_statuses') }}</option>
                        <option value="active">{{ __('ui.active') }}</option>
                        <option value="inactive">{{ __('ui.inactive') }}</option>
                    </select>
                </div>

                <!-- Creator Filter -->
                <div class="sm:w-44">
                    <label for="creator-filter" class="sr-only">{{ __('ui.creator_status') }}</label>
                    <select
                        id="creator-filter"
                        wire:model.live="creatorFilter"
                        class="ui-select block w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                    >
                        <option value="">{{ __('ui.all') }}</option>
                        <option value="pending">{{ __('ui.pending_approval') }}</option>
                        <option value="approved">{{ __('ui.approved_creators') }}</option>
                    </select>
                </div>

                <!-- Reset -->
                <button
                    wire:click="resetFilters"
                    type="button"
                    class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors text-sm font-medium"
                >
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    {{ __('ui.reset_filters') }}
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <button wire:click="sortByName" class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200">
                                    {{ __('ui.user') }}
                                    @if($sortBy === 'name')
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($sortDirection === 'asc')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            @endif
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.role') }}</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.status') }}</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.content') }}</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <button wire:click="sortByCreatedAt" class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200">
                                    {{ __('ui.joined') }}
                                    @if($sortBy === 'created_at')
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($sortDirection === 'asc')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            @endif
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th scope="col" class="px-6 py-3.5 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0">
                                            @if($user->avatar_url)
                                                <img class="h-9 w-9 rounded-full object-cover ring-2 ring-gray-100 dark:ring-gray-700" src="{{ $user->avatar_url }}" alt="">
                                            @else
                                                <div class="h-9 w-9 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-semibold ring-2 ring-gray-100 dark:ring-gray-700">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php $userRole = $user->getRoleNames()->first(); @endphp
                                    @if($userRole === 'admin')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-300 ring-1 ring-red-600/10 dark:ring-red-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                            {{ __('ui.admin') }}
                                        </span>
                                    @elseif($userRole === 'creator')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-purple-50 dark:bg-purple-500/10 text-purple-700 dark:text-purple-300 ring-1 ring-purple-600/10 dark:ring-purple-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                            {{ __('ui.creator') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 ring-1 ring-gray-500/10 dark:ring-gray-400/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                            {{ __('ui.user') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1.5">
                                        @if($user->is_active)
                                            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-green-700 dark:text-green-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                                {{ __('ui.active') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-red-700 dark:text-red-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                {{ __('ui.inactive') }}
                                            </span>
                                        @endif
                                        @if($user->creator_requested_at)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-300 ring-1 ring-amber-600/10 dark:ring-amber-500/20 w-fit">
                                                {{ __('ui.pending_creator') }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="inline-flex items-center gap-1" title="{{ __('ui.posts') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            {{ $user->posts_count }}
                                        </span>
                                        <span class="inline-flex items-center gap-1" title="{{ __('ui.courses') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                            {{ $user->courses_count }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $user->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-1">
                                        <!-- View -->
                                        <button
                                            wire:click="openDetailModal({{ $user->id }})"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors"
                                            title="{{ __('ui.view_details') }}"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>

                                        <!-- Role -->
                                        <button
                                            wire:click="openRoleModal({{ $user->id }})"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-500/10 transition-colors"
                                            title="{{ __('ui.change_role') }}"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        </button>

                                        <!-- Toggle Status -->
                                        @if($user->is_active)
                                            <button
                                                wire:click="openDeactivateModal({{ $user->id }})"
                                                class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors"
                                                title="{{ __('ui.deactivate') }}"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                            </button>
                                        @else
                                            <button
                                                wire:click="openActivateModal({{ $user->id }})"
                                                class="p-1.5 rounded-lg text-gray-400 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-500/10 transition-colors"
                                                title="{{ __('ui.activate') }}"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </button>
                                        @endif

                                        <!-- Approve/Reject Creator -->
                                        @if($user->creator_requested_at)
                                            <button
                                                wire:click="openApproveCreatorModal({{ $user->id }})"
                                                class="p-1.5 rounded-lg text-gray-400 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-500/10 transition-colors"
                                                title="{{ __('ui.approve_creator') }}"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </button>
                                            <button
                                                wire:click="openRejectCreatorModal({{ $user->id }})"
                                                class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors"
                                                title="{{ __('ui.reject_creator') }}"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        @endif

                                        <!-- Delete -->
                                        <button
                                            wire:click="openDeleteModal({{ $user->id }})"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors"
                                            title="{{ __('ui.delete') }}"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    <h3 class="mt-4 text-sm font-medium text-gray-900 dark:text-white">{{ __('ui.no_users_found') }}</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('ui.try_different_filters') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Role Modal -->
    @if($showRoleModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-900/70 dark:bg-gray-950/80" wire:click="closeModals"></div>

                <!-- Modal panel -->
                <div class="relative transform overflow-hidden rounded-xl bg-white dark:bg-gray-800 text-left shadow-xl transition-opacity duration-150 sm:my-8 sm:w-full sm:max-w-md">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="modal-title">{{ __('ui.change_user_role') }}</h3>
                    </div>

                    @if($selectedUser)
                            <div class="px-6 py-4">
                                <div class="flex items-center gap-3 mb-5 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    @if($selectedUser->avatar_url)
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $selectedUser->avatar_url }}" alt="">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm">
                                            {{ strtoupper(substr($selectedUser->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $selectedUser->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $selectedUser->email }}</p>
                                    </div>
                                </div>

                                <label for="modal-role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('ui.select_role') }}</label>
                                <select
                                    id="modal-role"
                                    wire:model="selectedRole"
                                    class="block w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                >
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                                @error('selectedRole')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 flex justify-end gap-3">
                                <button
                                    wire:click="closeModals"
                                    type="button"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
                                >
                                    {{ __('ui.cancel') }}
                                </button>
                                <button
                                    wire:click="saveRole"
                                    type="button"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                                >
                                    {{ __('ui.save') }}
                                </button>
                            </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Deactivate Modal -->
    @if($showDeactivateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="deactivate-modal-title" role="dialog" aria-modal="true">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-900/70 dark:bg-gray-950/80" wire:click="closeModals"></div>

                <!-- Modal panel -->
                <div class="relative transform overflow-hidden rounded-xl bg-white dark:bg-gray-800 text-left shadow-xl transition-opacity duration-150 sm:my-8 sm:w-full sm:max-w-md">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="deactivate-modal-title">{{ __('ui.deactivate_user') }}</h3>
                        </div>
                    </div>

                    @if($selectedUser)
                            <div class="px-6 py-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    {{ __('ui.deactivate_user_message', ['name' => $selectedUser->name]) }}
                                </p>

                                <label for="deactivation-reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('ui.deactivation_reason') }} <span class="text-red-500">*</span>
                                </label>
                                <textarea
                                    id="deactivation-reason"
                                    wire:model="deactivationReason"
                                    rows="3"
                                    placeholder="{{ __('ui.deactivation_reason_placeholder') }}"
                                    class="block w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm resize-none"
                                ></textarea>
                                @error('deactivationReason')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 flex justify-end gap-3">
                                <button
                                    wire:click="closeModals"
                                    type="button"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
                                >
                                    {{ __('ui.cancel') }}
                                </button>
                                <button
                                    wire:click="deactivateUser"
                                    type="button"
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors"
                                >
                                    {{ __('ui.deactivate') }}
                                </button>
                            </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Detail Modal -->
    @if($showDetailModal && $selectedUser)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="detail-modal-title" role="dialog" aria-modal="true">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-900/70 dark:bg-gray-950/80" wire:click="closeModals"></div>

                <!-- Modal panel -->
                <div class="relative transform overflow-hidden rounded-xl bg-white dark:bg-gray-800 text-left shadow-xl transition-opacity duration-150 sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="detail-modal-title">{{ __('ui.user_details') }}</h3>
                        <button wire:click="closeModals" type="button" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="px-6 py-5">
                        <!-- User Header -->
                        <div class="flex items-center gap-4 mb-6">
                            @if($selectedUser->avatar_url)
                                <img class="h-14 w-14 rounded-full object-cover ring-2 ring-gray-100 dark:ring-gray-700" src="{{ $selectedUser->avatar_url }}" alt="">
                            @else
                                <div class="h-14 w-14 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-xl font-semibold ring-2 ring-gray-100 dark:ring-gray-700">
                                    {{ strtoupper(substr($selectedUser->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $selectedUser->name }}</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedUser->email }}</p>
                                <div class="flex items-center gap-2 mt-1.5">
                                    @php $role = $selectedUser->getRoleNames()->first(); @endphp
                                    @if($role === 'admin')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-300">{{ __('ui.admin') }}</span>
                                    @elseif($role === 'creator')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-50 dark:bg-purple-500/10 text-purple-700 dark:text-purple-300">{{ __('ui.creator') }}</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">{{ __('ui.user') }}</span>
                                    @endif
                                    @if($selectedUser->is_active)
                                        <span class="inline-flex items-center gap-1 text-xs text-green-600 dark:text-green-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>{{ __('ui.active') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs text-red-600 dark:text-red-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>{{ __('ui.inactive') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-4 gap-3 mb-6">
                            <div class="text-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $selectedUser->posts_count }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('ui.posts') }}</p>
                            </div>
                            <div class="text-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $selectedUser->courses_count }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('ui.courses') }}</p>
                            </div>
                            <div class="text-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $selectedUser->resources_count }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('ui.resources') }}</p>
                            </div>
                            <div class="text-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $selectedUser->created_at->diffForHumans(null, true) }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('ui.member_since') }}</p>
                            </div>
                        </div>

                        <!-- Creator Request -->
                        @if($selectedUser->creator_requested_at)
                            <div class="mb-5 p-4 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 rounded-lg">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-medium text-amber-800 dark:text-amber-300">{{ __('ui.creator_request_pending') }}</p>
                                        <p class="text-xs text-amber-600 dark:text-amber-400 mt-0.5">{{ $selectedUser->creator_requested_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button
                                            wire:click="openApproveCreatorModal({{ $selectedUser->id }})"
                                            class="px-3 py-1.5 text-xs font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors"
                                        >
                                            {{ __('ui.approve') }}
                                        </button>
                                        <button
                                            wire:click="openRejectCreatorModal({{ $selectedUser->id }})"
                                            class="px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors"
                                        >
                                            {{ __('ui.reject') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Deactivation Reason -->
                        @if(!$selectedUser->is_active && $selectedUser->deactivation_reason)
                            <div class="mb-5 p-4 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-lg">
                                <p class="text-sm font-medium text-red-800 dark:text-red-300 mb-1">{{ __('ui.deactivation_reason') }}</p>
                                <p class="text-sm text-red-700 dark:text-red-400">{{ $selectedUser->deactivation_reason }}</p>
                            </div>
                        @endif

                        <!-- Info -->
                        <div class="space-y-3">
                            @if($selectedUser->bio)
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ __('ui.bio') }}</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white">{{ $selectedUser->bio }}</dd>
                                </div>
                            @endif
                            @if($selectedUser->tagline)
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ __('ui.tagline') }}</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white">{{ $selectedUser->tagline }}</dd>
                                </div>
                            @endif
                            @if($selectedUser->subscription_price)
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ __('ui.subscription_price') }}</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white">€{{ number_format($selectedUser->subscription_price, 2) }}/{{ __('ui.month') }}</dd>
                                </div>
                            @endif
                            @if($selectedUser->email_verified_at)
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ __('ui.email_verified') }}</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white">{{ $selectedUser->email_verified_at->format('d/m/Y H:i') }}</dd>
                                </div>
                            @endif
                        </div>

                        <!-- Permissions -->
                        @if($selectedUser->permissions->isNotEmpty())
                            <div class="mt-5">
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">{{ __('ui.permissions') }}</dt>
                                <dd class="flex flex-wrap gap-1.5">
                                    @foreach($selectedUser->permissions as $permission)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-300">
                                            {{ $permission->name }}
                                        </span>
                                    @endforeach
                                </dd>
                            </div>
                        @endif
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 flex justify-end gap-3">
                        <button
                            wire:click="openRoleModal({{ $selectedUser->id }})"
                            type="button"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
                        >
                            {{ __('ui.change_role') }}
                        </button>
                        @if($selectedUser->is_active)
                            <button
                                wire:click="openDeactivateModal({{ $selectedUser->id }})"
                                type="button"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors"
                            >
                                {{ __('ui.deactivate') }}
                            </button>
                        @else
                            <button
                                wire:click="openActivateModal({{ $selectedUser->id }})"
                                type="button"
                                class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors"
                            >
                                {{ __('ui.activate') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Activate Modal -->
    @if($showActivateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="activate-modal-title" role="dialog" aria-modal="true">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="fixed inset-0 bg-gray-900/70 dark:bg-gray-950/80" wire:click="closeModals"></div>
                <div class="relative transform overflow-hidden rounded-xl bg-white dark:bg-gray-800 text-left shadow-xl transition-opacity duration-150 sm:my-8 sm:w-full sm:max-w-md">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="activate-modal-title">{{ __('ui.activate_user') }}</h3>
                        </div>
                    </div>
                    @if($selectedUser)
                        <div class="px-6 py-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('ui.confirm_activate_user', ['name' => $selectedUser->name]) }}</p>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 flex justify-end gap-3">
                            <button wire:click="closeModals" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600">{{ __('ui.cancel') }}</button>
                            <button wire:click="activateUser" type="button" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">{{ __('ui.activate') }}</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Approve Creator Modal -->
    @if($showApproveCreatorModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="approve-modal-title" role="dialog" aria-modal="true">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="fixed inset-0 bg-gray-900/70 dark:bg-gray-950/80" wire:click="closeModals"></div>
                <div class="relative transform overflow-hidden rounded-xl bg-white dark:bg-gray-800 text-left shadow-xl transition-opacity duration-150 sm:my-8 sm:w-full sm:max-w-md">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="approve-modal-title">{{ __('ui.approve_creator') }}</h3>
                        </div>
                    </div>
                    @if($selectedUser)
                        <div class="px-6 py-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('ui.confirm_approve_creator_user', ['name' => $selectedUser->name]) }}</p>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 flex justify-end gap-3">
                            <button wire:click="closeModals" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600">{{ __('ui.cancel') }}</button>
                            <button wire:click="approveCreator" type="button" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">{{ __('ui.approve') }}</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Reject Creator Modal -->
    @if($showRejectCreatorModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="reject-modal-title" role="dialog" aria-modal="true">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="fixed inset-0 bg-gray-900/70 dark:bg-gray-950/80" wire:click="closeModals"></div>
                <div class="relative transform overflow-hidden rounded-xl bg-white dark:bg-gray-800 text-left shadow-xl transition-opacity duration-150 sm:my-8 sm:w-full sm:max-w-md">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="reject-modal-title">{{ __('ui.reject_creator') }}</h3>
                        </div>
                    </div>
                    @if($selectedUser)
                        <div class="px-6 py-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('ui.confirm_reject_creator_user', ['name' => $selectedUser->name]) }}</p>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 flex justify-end gap-3">
                            <button wire:click="closeModals" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600">{{ __('ui.cancel') }}</button>
                            <button wire:click="rejectCreator" type="button" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">{{ __('ui.reject') }}</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="delete-modal-title" role="dialog" aria-modal="true">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="fixed inset-0 bg-gray-900/70 dark:bg-gray-950/80" wire:click="closeModals"></div>
                <div class="relative transform overflow-hidden rounded-xl bg-white dark:bg-gray-800 text-left shadow-xl transition-opacity duration-150 sm:my-8 sm:w-full sm:max-w-md">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="delete-modal-title">{{ __('ui.delete_user') }}</h3>
                        </div>
                    </div>
                    @if($selectedUser)
                        <div class="px-6 py-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('ui.confirm_delete_user_message', ['name' => $selectedUser->name]) }}</p>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 flex justify-end gap-3">
                            <button wire:click="closeModals" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600">{{ __('ui.cancel') }}</button>
                            <button wire:click="deleteUser" type="button" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">{{ __('ui.delete') }}</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
