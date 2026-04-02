<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Webhooks\StripeWebhookHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use UnexpectedValueException;

final class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request, StripeWebhookHandler $handler): Response
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = (string) config('services.stripe.webhook_secret');

        if ($endpointSecret === '') {
            abort(500, 'Stripe webhook secret is not configured');
        }

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (SignatureVerificationException|UnexpectedValueException $e) {
            abort(400, 'Invalid webhook signature');
        }

        match ($event->type) {
            'invoice.payment_succeeded' => $handler->handleInvoicePaymentSucceeded($event->data->toArray()),
            'invoice.payment_failed' => $handler->handleInvoicePaymentFailed($event->data->toArray()),
            'customer.subscription.deleted' => $handler->handleCustomerSubscriptionDeleted($event->data->toArray()),
            default => Log::info('Unhandled Stripe event', ['type' => $event->type]),
        };

        return response('Webhook handled', 200);
    }
}
