<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrolment;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        // Default values
        $data = [
            'total_users' => 0,
            'total_courses' => 0,
            'total_enrolments' => 0,
            'recent_users' => collect(),
            'enrolled_courses' => collect(),
        ];

        if ($user->isAdmin()) {
            $data['total_users'] = User::count();
            $data['total_courses'] = Course::count();
            $data['total_enrolments'] = Enrolment::count();
            $data['recent_users'] = User::latest()->take(5)->get();
        } elseif ($user->isTeacher()) {
            $data['total_users'] = User::whereRelation('enrolments.course', 'created_by', $user->id)->distinct()->count();
            $data['total_courses'] = Course::where('created_by', $user->id)->count();
            $data['total_enrolments'] = Enrolment::whereRelation('course', 'created_by', $user->id)->count();
        } elseif ($user->isLearner()) {
            // Get courses where this user is enrolled
            $data['enrolled_courses'] = Course::whereRelation('enrolments', 'user_id', $user->id)->with('lessons', 'creator')->get();
            $data['total_courses'] = $data['enrolled_courses']->count();
        }

        return view('dashboard.index', $data);
    }
}
