<?php

declare(strict_types=1);

namespace App\Livewire\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Logout
{
    /**
     * Log the current user out of the application.
     *
     * @param  User|array{type: string, message: string}|null  $context
     * @param  array{type: string, message: string}|null  $toast
     */
    public function __invoke(User|array|null $context = null, ?array $toast = null): void
    {
        if (is_array($context) && $toast === null) {
            $toast = $context;
        }

        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        if ($toast) {
            session()->flash('toast', $toast);
        }
    }
}
