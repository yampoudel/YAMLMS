<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrolment;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class TrainingRecordController extends Controller
{
    /**
     * Display a listing of the training records based on user role.
     */
    public function index(): InertiaResponse
    {
        // Get the authenticated user
        $user = auth()->user();

        // LEARNER - Scoped Access to Own Records
        if ($user->isLearner()) {
            $records = Enrolment::where('user_id', $user->id)
                ->with(['course', 'user.courseProgress'])
                ->paginate(15)
                ->withQueryString();

            return Inertia::render('Learner/TrainingRecord/Index', [
                'records' => $records,
            ]);
        }

        // ADMIN - Global Unrestricted Access
        if ($user->isAdmin()) {
            $records = Enrolment::whereHas('user', fn ($query) => $query->where('role', 'learner'))
                ->with([
                    'course',
                    // Limits user columns and safely binds courseProgress inside one block
                    'user' => function ($query) {
                        $query->select('id', 'first_name', 'last_name')
                            ->with('courseProgress');
                    },
                ])
                ->paginate(15)
                ->withQueryString();

            return Inertia::render('Admin/TrainingRecord/Index', [
                'records' => $records,
            ]);
        }

        // TEACHER - Strictly Scoped Access
        if ($user->isTeacher()) {
            $course_ids = Course::where('created_by', $user->id)->pluck('id');

            $records = Enrolment::whereIn('course_id', $course_ids)
                ->whereHas('user', fn ($query) => $query->where('role', 'learner'))
                ->with([
                    'course',
                    // Scoped course progress layout for data isolation
                    'user' => function ($query) use ($course_ids) {
                        $query->select('id', 'first_name', 'last_name')
                            ->with(['courseProgress' => fn ($q) => $q->whereIn('course_id', $course_ids)]);
                    },
                ])
                ->paginate(15)
                ->withQueryString();

            return Inertia::render('Admin/TrainingRecord/Index', [
                'records' => $records,
            ]);
        }

        abort(403, 'Unauthorized access to training records.');
    }
}
