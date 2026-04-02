<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class CreatorSubscriptionController extends Controller
{
    public function store(Request $request, User $user, SubscriptionService $subscriptionService)
    {
        $subscriber = $request->user();

        if (! $subscriber) {
            abort(401);
        }

        if ($subscriber->id === $user->id) {
            throw new HttpException(403, 'You cannot subscribe to your own profile.');
        }

        if (! $user->hasRole('creator') || ! $user->is_active) {
            throw new HttpException(404, 'Creator not available.');
        }

        if ($subscriptionService->hasActiveSubscription($subscriber, $user)) {
            return redirect()
                ->route('creators.show', $user)
                ->with('toast', [
                    'type' => 'info',
                    'message' => __('ui.subscription_already_active'),
                ]);
        }

        try {
            return $subscriptionService->createCheckout($subscriber, $user);
        } catch (InvalidArgumentException $exception) {
            return redirect()
                ->route('creators.show', $user)
                ->with('toast', [
                    'type' => 'error',
                    'message' => $exception->getMessage(),
                ]);
        }
    }

    public function success(User $user): RedirectResponse
    {
        return redirect()
            ->route('creators.show', $user)
            ->with('toast', [
                'type' => 'success',
                'message' => __('ui.subscription_checkout_success'),
            ]);
    }

    public function cancel(User $user): RedirectResponse
    {
        return redirect()
            ->route('creators.show', $user)
            ->with('toast', [
                'type' => 'warning',
                'message' => __('ui.subscription_checkout_cancelled'),
            ]);
    }
}
