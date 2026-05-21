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
    /**
     * Create a secure Payment Intent session on Stripe's sandbox servers.
     * Accessible at: POST /api/integrations/stripe/intent/{course}
     */
    public function createIntent($id)
    {
        try {
            // Explicitly fetch the course inside the safety net to catch database errors
            $course = Course::findOrFail($id);

            if (! $course) {
                return response()->json([
                    'status' => 'error',
                    'message' => "LMS Error: Course ID {$id} does not exist in the database.",
                ], 404);
            }

            $user = auth('web')->user();

            if (! $user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User session not found. Please re-authenticate.',
                ], 401);
            }

            // Stripe take in as cents
            $amount_in_cents = (int) ($course->price * 100);

            if ($amount_in_cents <= 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Invalid course price tier detected for course: {$course->title}.",
                ], 400);
            }

            // Initialize a transaction payload on stripe sandbox server
            $intent = $this->stripe->paymentIntents->create([
                'amount' => $amount_in_cents,
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
                'amount' => $amount_in_cents,
                'status' => 'Pending',
            ]);

            // Return secrete key and payment intent id from frontend client
            return response()->json([
                'status' => 'success',
                'client_secret' => $intent->client_secret,
                'payment_intent_id' => $intent->id,
            ], 200);

        } catch (\Exception $e) {
            // ANY crash (including database column errors) will now be forced to show up here!
            return response()->json([
                'status' => 'error',
                'message' => 'CRASH CAUGHT: '.$e->getMessage().' in file '.$e->getFile().' on line '.$e->getLine(),
            ], 200); // Forcing a 200 status so your browser JS can read and alert the exact error string
        }
    }
}
