<div
    x-data="toastHandler()"
    @toast.window="addToast($event.detail)"
    class="fixed top-4 right-4 z-[100] flex flex-col gap-3 max-w-sm w-full pointer-events-none"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="toast.visible"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-8"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-8"
            class="pointer-events-auto"
        >
            <div
                class="relative flex items-start gap-3 p-4 rounded-lg shadow-lg border backdrop-blur-sm overflow-hidden"
                :class="{
                    'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700': toast.type === 'default',
                    'bg-emerald-50 dark:bg-emerald-900/30 border-emerald-200 dark:border-emerald-800': toast.type === 'success',
                    'bg-red-50 dark:bg-red-900/30 border-red-200 dark:border-red-800': toast.type === 'error',
                    'bg-amber-50 dark:bg-amber-900/30 border-amber-200 dark:border-amber-800': toast.type === 'warning',
                    'bg-blue-50 dark:bg-blue-900/30 border-blue-200 dark:border-blue-800': toast.type === 'info',
                }"
            >
                <div class="flex-shrink-0 mt-0.5">
                    <svg x-show="toast.type === 'success'" class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <svg x-show="toast.type === 'error'" class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <svg x-show="toast.type === 'warning'" class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    <svg x-show="toast.type === 'info'" class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                    <svg x-show="toast.type === 'default'" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 12h7.5m-3.75-3.75h.008v.008h-.008v-.008zm0 7.5h.008v.008h-.008v-.008z" />
                    </svg>
                </div>

                <div class="flex-1 min-w-0">
                    <p x-show="toast.title" class="text-sm font-medium"
                       :class="{
                           'text-gray-900 dark:text-white': toast.type === 'default',
                           'text-emerald-800 dark:text-emerald-200': toast.type === 'success',
                           'text-red-800 dark:text-red-200': toast.type === 'error',
                           'text-amber-800 dark:text-amber-200': toast.type === 'warning',
                           'text-blue-800 dark:text-blue-200': toast.type === 'info',
                       }"
                       x-text="toast.title"></p>
                    <p class="text-sm mt-0.5"
                       :class="{
                           'text-gray-600 dark:text-gray-300': toast.type === 'default',
                           'text-emerald-700 dark:text-emerald-300': toast.type === 'success',
                           'text-red-700 dark:text-red-300': toast.type === 'error',
                           'text-amber-700 dark:text-amber-300': toast.type === 'warning',
                           'text-blue-700 dark:text-blue-300': toast.type === 'info',
                       }"
                       x-text="toast.message"></p>
                </div>

                <button
                    @click="removeToast(toast.id)"
                    class="flex-shrink-0 p-1 rounded hover:bg-black/10 dark:hover:bg-white/10 transition-colors"
                >
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div
                    x-show="toast.duration > 0"
                    class="absolute bottom-0 left-0 right-0 h-0.5"
                >
                    <div
                        class="h-full"
                        :class="{
                            'bg-emerald-500/50': toast.type === 'success',
                            'bg-red-500/50': toast.type === 'error',
                            'bg-amber-500/50': toast.type === 'warning',
                            'bg-blue-500/50': toast.type === 'info',
                            'bg-gray-500/50': toast.type === 'default',
                        }"
                        :style="`animation: toast-progress ${toast.duration}ms linear forwards`"
                    ></div>
                </div>
            </div>
        </div>
    </template>
</div>

@if(session('toast'))
    <script id="__toastFlash" type="application/json">{!! json_encode(session('toast'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
@endif
