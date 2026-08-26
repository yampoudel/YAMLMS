<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class FrontController extends Controller
{
    /**
     * Display the public landing page with recent courses.
     */
    public function index(Request $request): InertiaResponse
    {
        // Fetch the 6 most recently created courses
        $courses = Course::latest()->take(6)->get();

        return Inertia::render('Front/Home', [
            'courses' => $courses,
        ]);
    }
}
