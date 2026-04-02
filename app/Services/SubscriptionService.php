<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use InvalidArgumentException;
use Laravel\Cashier\Checkout;
use Laravel\Cashier\Subscription;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class SubscriptionService
{
    public function __construct(
        private readonly StripeClient $stripe,
    ) {}

    public function hasActiveSubscription(User $user, User $creator): bool
    {
        if (! $creator->hasRole('creator') && ! $creator->hasRole('admin')) {
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

    public function createCheckout(User $subscriber, User $creator): Checkout
    {
        $priceId = $this->ensureStripePriceForCreator($creator);

        return $subscriber
            ->newSubscription($this->getSubscriptionType($creator), $priceId)
            ->checkout([
                'success_url' => route('creators.subscribe.success', $creator),
                'cancel_url' => route('creators.subscribe.cancel', $creator),
                'metadata' => [
                    'creator_id' => (string) $creator->id,
                    'subscriber_id' => (string) $subscriber->id,
                ],
            ]);
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

    private function ensureStripePriceForCreator(User $creator): string
    {
        if ($creator->stripe_price_id) {
            return $creator->stripe_price_id;
        }

        $amount = (int) round($creator->getActiveSubscriptionPrice() * 100);

        if ($amount < 50) {
            throw new InvalidArgumentException('Subscription amount must be at least 0.50.');
        }

        try {
            $product = $this->stripe->products->create([
                'name' => sprintf('MonetizaYa - %s subscription', $creator->name),
                'metadata' => [
                    'creator_id' => (string) $creator->id,
                ],
            ]);

            $price = $this->stripe->prices->create([
                'product' => $product->id,
                'currency' => strtolower((string) config('cashier.currency', 'eur')),
                'unit_amount' => $amount,
                'recurring' => [
                    'interval' => 'month',
                ],
                'metadata' => [
                    'creator_id' => (string) $creator->id,
                ],
            ]);
        } catch (ApiErrorException $exception) {
            throw new InvalidArgumentException('Unable to create Stripe price for this creator.', previous: $exception);
        }

        $creator->forceFill([
            'stripe_price_id' => $price->id,
        ])->save();

        return $price->id;
    }
}
