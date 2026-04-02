<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->routeIs('auth.status')) {
            return $next($request);
        }

        $user = Auth::user();

        if ($user && ! $user->is_active) {
            $reason = $user->deactivation_reason;
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            session()->flash('toast', [
                'type' => 'error',
                'message' => $reason
                    ? trans('auth.account_deactivated_with_reason', ['reason' => $reason])
                    : trans('auth.account_deactivated'),
            ]);

            return redirect()->route('login');
        }

        return $next($request);
    }
}
