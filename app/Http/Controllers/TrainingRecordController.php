<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCompleted;
use App\Models\User;

class TrainingRecordController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // LEARNER: Only see their own specific completed courses
        if ($user->isLearner()) {
            $records = CourseCompleted::where('user_id', $user->id)
                ->with('course') // Eager load course details
                ->get();

        }

        // TEACHER/ADMIN: See the Global Roster
        if ($user->isTeacher() || $user->isAdmin()) {
            // Fetch all students for courses owned by the teacher (or all for Admin)
            $course_ids = $user->isAdmin()
                ? Course::pluck('id')
                : Course::where('created_by', $user->id)->pluck('id');

            $records = User::where('role', 'learner')
                ->whereHas('enrolments', fn ($q) => $q->whereIn('course_id', $course_ids))
                ->with(['enrolments.course', 'courseProgress' => fn ($q) => $q->whereIn('course_id', $course_ids)])
                ->get();
        }

        return view('training-record.index', compact('records'));

        abort(403);
    }
}
