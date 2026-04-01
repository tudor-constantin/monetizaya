<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }
        Auth::user()->sendEmailVerificationNotification();
        session()->flash('toast', [
            'type' => 'success',
            'message' => __('ui.verification_link_sent'),
        ]);
    }

    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('ui.verify_email') }}</h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('ui.verify_email_desc') }}</p>
    </div>

    <div class="flex items-center justify-between">
        <button wire:click="sendVerification" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled">
            <span wire:loading.remove>{{ __('ui.resend_verification') }}</span>
            <span wire:loading>Sending...</span>
        </button>

        <button wire:click="logout" type="button" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white underline">
            {{ __('ui.log_out') }}
        </button>
    </div>
</div>
