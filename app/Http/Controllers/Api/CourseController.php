<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CourseController extends Controller
{
    /**
     * return public list of courses
     */
    public function index(): AnonymousResourceCollection
    {
        $courses = Course::with(['lessons', 'creator'])->paginate(10);

        // Wrap and return as a resource collection
        return CourseResource::collection($courses);
    }

    /**
     * return list of courses for the  user
     */
    public function myCourses(Request $request): AnonymousResourceCollection
    {
        // Fetch data with relationships to avoid N+1 queries
        $courses = $request->user()
            ->courses()
            ->with(['lessons', 'creator'])
            ->paginate(5);

        // Wrap and return as a resource collection
        return CourseResource::collection($courses);
    }

    /**
     * Return list of courses and their progress
     */
    public function progress(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();

        $courses = $user->courses()
            ->with(['courseProgress' => function ($q) use ($user) {
                $q->where('user_id', $user->id); // This filters only this students row
            }])
            ->get();

        return CourseResource::collection($courses);
    }

    /**
     * Show lessons and creator within a course
     */
    public function show(Request $request, Course $course): CourseResource|JsonResponse
    {
        // Only able to view the course assign to user
        if (! $request->user()->courses()->where('course_id', $course->id)->exists()) {
            return response()->json([
                'error' => 'unauthorized_access',
                'message' => 'You are not enrolled in this course.',
            ], 403);
        }

        // Load lessons so that learner can view lessons and creator
        return new CourseResource($course->load(['lessons', 'creator']));
    }
}
