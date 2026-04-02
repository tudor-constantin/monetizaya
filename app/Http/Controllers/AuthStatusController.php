<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthStatusController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'active' => false,
                'redirect' => route('login'),
            ]);
        }

        if ($user->is_active) {
            return response()->json([
                'active' => true,
            ]);
        }

        $reason = $user->deactivation_reason;

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'active' => false,
            'redirect' => route('login'),
            'toast' => [
                'type' => 'error',
                'message' => $reason
                    ? trans('auth.account_deactivated_with_reason', ['reason' => $reason])
                    : trans('auth.account_deactivated'),
            ],
        ], 423);
    }
}
