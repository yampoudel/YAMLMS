<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrolment;
use App\Models\User;
use App\Models\Order;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class DashboardController extends Controller
{
    public function index(): InertiaResponse
    {
        $user = auth()->user();

        // Default dashboard data structure
        $data = [
            'total_users' => 0,
            'total_courses' => 0,
            'total_enrolments' => 0,
            'recent_users' => collect(),
            'enrolled_courses' => collect(),
        ];

        // Staff Routing (Admin & Teacher)
        if ($user->isAdmin() || $user->isTeacher()) {

            $data['recent_users'] = User::latest()->take(5)->get()->values();

            if ($user->isAdmin()) {
                $data['total_users'] = User::count();
                $data['total_courses'] = Course::count();
                $data['total_enrolments'] = Enrolment::count();
            } elseif ($user->isTeacher()) {
                $data['total_users'] = User::whereRelation('enrolments.course', 'created_by', $user->id)->distinct()->count();
                $data['total_courses'] = Course::where('created_by', $user->id)->count();
                $data['total_enrolments'] = Enrolment::whereRelation('course', 'created_by', $user->id)->count();
            }

            return Inertia::render('Admin/Dashboard/Index', [
                'data' => $data,
            ]);
        }

        // Student routing
        if ($user->isLearner()) {
            // Synchronous fallback handler to capture instant payment returns
            if (request()->get('payment_success') === '1') {
                try {
                    $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
                    $pendingOrders = Order::where('user_id', $user->id)
                        ->where('status', 'Pending')
                        ->get();

                    foreach ($pendingOrders as $order) {
                        $intent = $stripe->paymentIntents->retrieve($order->stripe_payment_intent_id);

                        if ($intent->status === 'succeeded') {
                            $order->update(['status' => 'Completed']);

                            Enrolment::updateOrCreate(
                                ['user_id' => $user->id, 'course_id' => $order->course_id],
                                ['status' => 'Active']
                            );
                        }
                    }
                } catch (\Exception $e) {
                    // Fail silently
                }
            }

            $data['enrolled_courses'] = $user->courses()
                ->with(['creator'])
                ->withCount('lessons')
                ->get()
                ->map(function ($course) use ($user) {
                    // Check pending order
                    $existingOrder = Order::where('user_id', $user->id)
                        ->where('course_id', $course->id)
                        ->where('status', 'Pending')
                        ->first();

                    // Get latest enrolment status
                    $liveEnrolment = Enrolment::where('user_id', $user->id)
                        ->where('course_id', $course->id)
                        ->first();

                    // Sync pivot values for Vue
                    $course->pivot->status = $liveEnrolment ? $liveEnrolment->status : 'Pending_Payment';
                    $course->pivot->stripe_client_secret = $existingOrder ? $existingOrder->stripe_payment_intent_id : null;

                    return $course;
                })
                ->values();

            $data['total_courses'] = $data['enrolled_courses']->count();
        }

        return Inertia::render('Learner/Dashboard/Index', [
            'data' => $data,
            'stripe_publishable_key' => env('STRIPE_KEY'),
        ]);
    }
}
