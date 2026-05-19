<?php

namespace App\Http\Controllers\Api\Integrations;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Order;
use Stripe\StripeClient;

class StripeCheckoutController extends Controller
{
    private $stripe;

    public function __construct()
    {
        // Safely loads your sk_test sandbox token from configuration files
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    /**
     * Create a secure Payment Intent session on Stripe's sandbox servers.
     * Accessible at: POST /api/integrations/stripe/intent/{course}
     */
    public function createIntent(Course $course)
    {
        $user = auth()->user();

        try {
            // Initialize a transaction payload on stripe sandbox server
            $intent = $this->stripe->paymentIntents->create([
                'amount' => $course->price * 100,
                'currency' => 'AUD',
                'automatic_payment_methods' => ['enabled' => true],
                'metadata' => [
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                ],
            ]);

            // Create the records inside lms_orders and set status to 'pending'
            Order::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'stripe_payment_intent_id' => $intent->id,
                'amount' => $course->price * 100, // stores in cents as stripe architecture
                'status' => 'Pending',
            ]);

            // Return secrete key and payment intent id from frontend client
            return response()->json([
                'status' => 'success',
                'client_secret' => $intent->client_secret,
                'payment_intent_id' => $intent->id,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
