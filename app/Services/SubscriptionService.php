<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Laravel\Cashier\Subscription;

class SubscriptionService
{
    public function hasActiveSubscription(User $user, User $creator): bool
    {
        if (! $creator->is_creator) {
            return false;
        }

        return $user->subscriptions()
            ->where('stripe_status', 'active')
            ->where('type', $this->getSubscriptionType($creator))
            ->exists();
    }

    public function getSubscriptionType(User $creator): string
    {
        return 'creator-'.$creator->id;
    }

    public function subscribe(User $subscriber, User $creator, string $priceId): Subscription
    {
        return $subscriber->newSubscription($this->getSubscriptionType($creator), $priceId)->create();
    }

    public function cancel(User $subscriber, User $creator): void
    {
        $subscription = $subscriber->subscription($this->getSubscriptionType($creator));

        if ($subscription) {
            $subscription->cancel();
        }
    }

    public function resume(User $subscriber, User $creator): void
    {
        $subscription = $subscriber->subscription($this->getSubscriptionType($creator));

        if ($subscription) {
            $subscription->resume();
        }
    }
}
