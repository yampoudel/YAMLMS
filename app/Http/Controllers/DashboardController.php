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

        // Learner Routing
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

            // Preload orders and completions into memory maps for efficient lookups
            $ordersMap = Order::where('user_id', $user->id)
                ->get()
                ->keyBy('course_id');

            $completionsMap = CourseCompleted::where('user_id', $user->id)
                ->get()
                ->keyBy('course_id');

            // Fetch enrolled courses with eager loading and map to include progress tracking
            $data['enrolled_courses'] = $user->courses()
                ->with(['creator'])
                ->withCount('lessons')
                ->get()
                ->map(function ($course) use ($ordersMap, $completionsMap) {
                    // Retrieve the corresponding order and completion records for this course
                    $orderRecord = $ordersMap->get($course->id);
                    $courseCompleted = $completionsMap->get($course->id);

                    // Fetch the real first lesson ID dynamically for this course
                    $firstLesson = $course->lessons()->orderBy('id', 'asc')->first();

                    // Construct a clean progress tracking object to attach to the course model
                    $progressData = [
                        'first_lesson_id' => $firstLesson ? $firstLesson->id : null,
                        'status' => ($orderRecord && $orderRecord->status === 'Completed') ? 'Active' : 'Pending_Payment',
                        'stripe_client_secret' => $orderRecord ? $orderRecord->stripe_payment_intent_id : null,
                        'completion_status' => $courseCompleted ? $courseCompleted->status : 'Not_Started',
                        'progress_percentage' => $courseCompleted ? (int) $courseCompleted->progress_percentage : 0,
                    ];

                    // If the course is completed or progress is 100%, ensure the status reflects completion
                    if ($progressData['completion_status'] === 'Completed' || $progressData['progress_percentage'] >= 100) {
                        $progressData['status'] = 'Active';
                        $progressData['completion_status'] = 'Completed';
                        $progressData['progress_percentage'] = 100;
                    }

                    // Attach the progress data as a clean object to the course model
                    $course->progress = (object) $progressData;

                    // Remove the pivot data to avoid exposing unnecessary relational data
                    unset($course->pivot);

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
