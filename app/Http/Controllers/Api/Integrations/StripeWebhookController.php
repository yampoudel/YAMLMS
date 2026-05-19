<?php

namespace App\Http\Controllers\Api\Integrations;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    /**
     * Handle incoming secure events directly from Stripe's sandbox servers.
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');

        // Securely reads the whsec_ key from config/services.php
        $endpoint_secret = config('services.stripe.webhook_secret');

        // Verify the cryptographic signature to ensure it actually came from Stripe
        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid Signature Signature Verification Failed'], 400);
        }

        // Process successful payment approvals
        if ($event->type === 'payment_intent.succeeded') {
            $payment_intent = $event->data->object;

            // Search the ledger for the matching intent record string
            $order = Order::where('stripe_payment_intent_id', $payment_intent->id)->first();

            // 3. Mark the record as Completed
            if ($order && $order->status !== 'Completed') {
                $order->update([
                    'status' => 'Completed',
                ]);

                // Enrolment attachment logic is paused for now as per your strategy,
                // and will be handled during the Admin refactor PR.
            }
        }

        // Always return a clean 200 success code so Stripe stops retrying the event
        return response()->json(['status' => 'success'], 200);
    }
}
