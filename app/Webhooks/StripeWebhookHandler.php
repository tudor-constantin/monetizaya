<?php

declare(strict_types=1);

namespace App\Webhooks;

use App\Models\Transaction;
use App\Models\User;
use App\Services\RevenueService;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Subscription;

class StripeWebhookHandler
{
    public function __construct(
        protected RevenueService $revenueService,
    ) {}

    public function handleInvoicePaymentSucceeded(array $payload): void
    {
        $invoice = $payload['data']['object'];
        $subscriptionId = $invoice['subscription'] ?? null;

        if (! $subscriptionId) {
            return;
        }

        $subscription = Subscription::where('stripe_id', $subscriptionId)->first();

        if (! $subscription) {
            Log::warning('Stripe webhook: subscription not found', ['stripe_id' => $subscriptionId]);

            return;
        }

        $subscriber = $subscription->user;
        $creatorId = $this->extractCreatorIdFromSubscription($subscription);
        $creator = User::find($creatorId);

        if (! $creator) {
            Log::warning('Stripe webhook: creator not found', ['creator_id' => $creatorId]);

            return;
        }

        $grossAmount = $invoice['amount_paid'] / 100;
        $currency = $invoice['currency'] ?? 'eur';
        $paymentIntent = $invoice['payment_intent'] ?? null;
        $invoiceId = $invoice['id'] ?? null;

        if ($invoiceId && Transaction::where('stripe_invoice_id', $invoiceId)->exists()) {
            Log::info('Skipping duplicated Stripe invoice webhook', ['stripe_invoice_id' => $invoiceId]);

            return;
        }

        $this->revenueService->recordTransaction(
            $subscriber,
            $creator,
            $grossAmount,
            $currency,
            'subscription',
            $paymentIntent,
            $invoiceId,
        );

        Log::info('Payment succeeded', [
            'subscriber_id' => $subscriber->id,
            'creator_id' => $creator->id,
            'amount' => $grossAmount,
        ]);
    }

    public function handleInvoicePaymentFailed(array $payload): void
    {
        $invoice = $payload['data']['object'];
        $subscriptionId = $invoice['subscription'] ?? null;

        if (! $subscriptionId) {
            return;
        }

        $subscription = Subscription::where('stripe_id', $subscriptionId)->first();

        if (! $subscription) {
            return;
        }

        Log::warning('Payment failed for subscription', [
            'subscription_id' => $subscriptionId,
            'user_id' => $subscription->user_id,
        ]);
    }

    public function handleCustomerSubscriptionDeleted(array $payload): void
    {
        $subscriptionId = $payload['data']['object']['id'] ?? null;

        if (! $subscriptionId) {
            return;
        }

        $subscription = Subscription::where('stripe_id', $subscriptionId)->first();

        if ($subscription) {
            $subscription->update(['stripe_status' => 'cancelled']);
        }

        Log::info('Subscription cancelled', ['stripe_id' => $subscriptionId]);
    }

    protected function extractCreatorIdFromSubscription(Subscription $subscription): ?int
    {
        $type = $subscription->type;

        if (str_starts_with($type, 'creator-')) {
            return (int) str_replace('creator-', '', $type);
        }

        return null;
    }
}
