<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCompleted;
use App\Models\Enrolment;
use App\Models\Order;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Stripe\StripeClient;

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
                    $stripe = new StripeClient(env('STRIPE_SECRET'));
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

            // Optimization: Fetch bulk lookups keyed by course_id to avoid N+1 queries
            $ordersMap = Order::where('user_id', $user->id)
                ->get()
                ->keyBy('course_id');

            $completionsMap = CourseCompleted::where('user_id', $user->id)
                ->get()
                ->keyBy('course_id');

            // Fetch courses from user relationship
            $data['enrolled_courses'] = $user->courses()
                ->with(['creator'])
                ->withCount('lessons')
                ->get()
                ->map(function ($course) use ($ordersMap, $completionsMap) {
                    // Pull matched records safely out of memory maps
                    $orderRecord = $ordersMap->get($course->id);
                    $courseCompleted = $completionsMap->get($course->id);

                    // Fetch the real first lesson ID dynamically for this course to avoid routing mismatches
                    $firstLesson = $course->lessons()->orderBy('id', 'asc')->first();
                    $course->pivot->first_lesson_id = $firstLesson ? $firstLesson->id : null;

                    // Map status explicitly from the Order table record state
                    if ($orderRecord && $orderRecord->status === 'Completed') {
                        $course->pivot->status = 'Active';
                    } else {
                        $course->pivot->status = 'Pending_Payment';
                    }

                    $course->pivot->stripe_client_secret = $orderRecord ? $orderRecord->stripe_payment_intent_id : null;

                    // Evaluate completion states clearly from lms_courses_completed table
                    if ($courseCompleted) {
                        $course->pivot->completion_status = $courseCompleted->status;
                        $course->pivot->progress_percentage = (int) $courseCompleted->progress_percentage;
                    } else {
                        $course->pivot->completion_status = 'Not_Started';
                        $course->pivot->progress_percentage = 0;
                    }

                    // Safety Override — If progress reads 100%, force status metrics to Active & Completed
                    if ($course->pivot->completion_status === 'Completed' || $course->pivot->progress_percentage >= 100) {
                        $course->pivot->status = 'Active';
                        $course->pivot->completion_status = 'Completed';
                        $course->pivot->progress_percentage = 100;
                    }

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
