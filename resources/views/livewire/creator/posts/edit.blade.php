<div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('ui.edit_post') }}</h1>
        </div>

        <form wire:submit="save" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 sm:p-8 space-y-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('ui.title') }}</label>
                <input wire:model.defer="title" id="title" type="text" required class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm px-4 py-2.5">
            </div>

            <div>
                <label for="excerpt" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('ui.excerpt') }}</label>
                <textarea wire:model.defer="excerpt" id="excerpt" rows="3" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm px-4 py-2.5" placeholder="{{ __('ui.excerpt_placeholder') }}"></textarea>
            </div>

            <div>
                <label for="body" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('ui.content') }}</label>
                <textarea wire:model.defer="body" id="body" rows="12" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm px-4 py-2.5"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('ui.post_cover') }}</label>
                <input wire:model="coverImageFile" type="file" accept="image/png,image/jpeg,image/webp" class="block w-full cursor-pointer text-sm text-gray-600 dark:text-gray-300 file:cursor-pointer file:mr-3 file:rounded-lg file:border-0 file:bg-blue-600 hover:file:bg-blue-700 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-white transition-colors">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('ui.post_cover_hint') }}</p>
                @if($coverImageFile)
                    <img src="{{ $coverImageFile->temporaryUrl() }}" alt="" class="mt-3 h-40 w-full object-cover rounded-lg border border-gray-200 dark:border-gray-700">
                @elseif($coverImageUrl)
                    <img src="{{ $coverImageUrl }}" alt="" class="mt-3 h-40 w-full object-cover rounded-lg border border-gray-200 dark:border-gray-700">
                @endif
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex-1">
                    <label class="ui-card-hover flex items-start gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer">
                        <span class="relative inline-flex items-center mt-0.5">
                            <input type="checkbox" wire:model.defer="isPremium" class="peer sr-only">
                            <span class="w-11 h-6 rounded-full bg-gray-300 dark:bg-gray-600 peer-checked:bg-blue-600 transition-colors"></span>
                            <span class="absolute left-0.5 top-0.5 w-5 h-5 rounded-full bg-white shadow-sm peer-checked:translate-x-5 transition-transform"></span>
                        </span>
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('ui.premium_content') }} <span class="block text-xs text-gray-500 dark:text-gray-400">{{ __('ui.only_for_subscribers') }}</span></span>
                    </label>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('ui.status') }}</label>
                    <div class="ui-card-hover rounded-lg relative">
                        <select wire:model.defer="status" id="status" class="block w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 sm:text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-600 dark:focus:border-blue-500 focus:ring-blue-600 dark:focus:ring-blue-500">
                            <option value="draft">{{ __('ui.draft') }}</option>
                            <option value="published">{{ __('ui.published') }}</option>
                            <option value="archived">{{ __('ui.archived') }}</option>
                        </select>
                        <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
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
