<div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-4">
                <a href="{{ route('creator.dashboard') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors" wire:navigate>{{ __('ui.dashboard') }}</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('creator.posts.index') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors" wire:navigate>{{ __('ui.posts') }}</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-gray-900 dark:text-white font-medium">{{ __('ui.edit_post') }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('ui.edit_post') }}</h1>
        </div>

        <form wire:submit="save" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 sm:p-8 space-y-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('ui.title') }}</label>
                <input wire:model.defer="title" id="title" type="text" required class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm px-4 py-2.5">
            </div>

            <div>
                <label for="body" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('ui.content') }}</label>
                <textarea wire:model.defer="body" id="body" rows="12" class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm px-4 py-3"></textarea>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex-1">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model.defer="isPremium" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('ui.premium_content') }} <span class="block text-xs text-gray-500 dark:text-gray-400">{{ __('ui.only_for_subscribers') }}</span></span>
                    </label>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('ui.status') }}</label>
                    <select wire:model.defer="status" id="status" class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm px-4 py-2.5">
                        <option value="draft">{{ __('ui.draft') }}</option>
                        <option value="published">{{ __('ui.published') }}</option>
                        <option value="archived">{{ __('ui.archived') }}</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('creator.posts.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors" wire:navigate>{{ __('ui.cancel') }}</a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ __('ui.save_changes') }}</span>
                    <span wire:loading>Saving...</span>
                </button>
            </div>
        </form>
    </div>
</div>
