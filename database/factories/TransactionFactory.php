<?php

namespace Database\Factories;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    public function definition(): array
    {
        $grossAmount = fake()->randomFloat(2, 5, 100);
        $platformFee = round($grossAmount * 0.10, 2);
        $netAmount = round($grossAmount - $platformFee, 2);

        return [
            'subscriber_id' => User::factory(),
            'creator_id' => User::factory(),
            'stripe_payment_intent_id' => 'pi_'.fake()->lexify('????????????????????????'),
            'stripe_invoice_id' => 'in_'.fake()->lexify('????????????????????????'),
            'gross_amount' => $grossAmount,
            'platform_fee' => $platformFee,
            'net_amount' => $netAmount,
            'currency' => 'eur',
            'status' => fake()->randomElement([TransactionStatus::COMPLETED, TransactionStatus::PENDING, TransactionStatus::FAILED]),
            'type' => TransactionType::SUBSCRIPTION,
            'paid_at' => fake()->optional(0.8)->dateTimeBetween('-6 months', 'now'),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TransactionStatus::COMPLETED,
            'paid_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TransactionStatus::PENDING,
            'paid_at' => null,
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TransactionStatus::FAILED,
            'paid_at' => null,
        ]);
    }
}
