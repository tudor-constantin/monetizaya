<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;

class RevenueService
{
    public function getPlatformCommissionPercentage(): float
    {
        return (float) config('app.platform_commission', 10.0);
    }

    public function calculatePlatformFee(float $grossAmount): float
    {
        return round($grossAmount * ($this->getPlatformCommissionPercentage() / 100), 2);
    }

    public function calculateNetAmount(float $grossAmount): float
    {
        return round($grossAmount - $this->calculatePlatformFee($grossAmount), 2);
    }

    public function recordTransaction(
        User $subscriber,
        User $creator,
        float $grossAmount,
        string $currency,
        string $type,
        ?string $stripePaymentIntentId = null,
        ?string $stripeInvoiceId = null,
    ): Transaction {
        $platformFee = $this->calculatePlatformFee($grossAmount);
        $netAmount = $this->calculateNetAmount($grossAmount);

        return Transaction::create([
            'subscriber_id' => $subscriber->id,
            'creator_id' => $creator->id,
            'stripe_payment_intent_id' => $stripePaymentIntentId,
            'stripe_invoice_id' => $stripeInvoiceId,
            'gross_amount' => $grossAmount,
            'platform_fee' => $platformFee,
            'net_amount' => $netAmount,
            'currency' => $currency,
            'type' => $type,
            'status' => 'completed',
            'paid_at' => now(),
        ]);
    }

    public function getCreatorEarnings(User $creator, ?string $period = null): array
    {
        $query = Transaction::where('creator_id', $creator->id)
            ->where('status', 'completed');

        if ($period === 'month') {
            $query->whereMonth('paid_at', now()->month)
                ->whereYear('paid_at', now()->year);
        }

        return $query->selectRaw('
            SUM(gross_amount) as gross_earnings,
            SUM(platform_fee) as total_fees,
            SUM(net_amount) as net_earnings,
            COUNT(*) as transaction_count
        ')->first()->toArray();
    }

    public function getPlatformRevenue(?string $period = null): array
    {
        $query = Transaction::where('status', 'completed');

        if ($period === 'month') {
            $query->whereMonth('paid_at', now()->month)
                ->whereYear('paid_at', now()->year);
        }

        return $query->selectRaw('
            SUM(platform_fee) as total_revenue,
            SUM(gross_amount) as total_gmv,
            COUNT(*) as transaction_count
        ')->first()->toArray();
    }
}
