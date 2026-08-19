<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class CourseController extends Controller
{
    /**
     * Dependency Injection from CourseService
     */
    public function __construct(protected CourseService $courseService) {}

    /**
     * Display a listing of the course.
     */
    public function index(Request $request): View|RedirectResponse|InertiaResponse
    {
        // Check policy
        $this->authorize('viewAny', Course::class);

        // Pass all filter inputs directly to the service
        $courses = $this->courseService->getCourseList(15, $request->only(['title', 'status']));

        return Inertia::render('Admin/Course/Index', [
            'courses' => $courses,
            'filters' => request()->only(['title', 'status']),
        ]);
    }

    /**
     * Show the form for creating a new course
     */
    public function create(): RedirectResponse|InertiaResponse
    {
        // Check policy
        if (Gate::denies('create', Course::class)) {
            return redirect()->route('courses.index')
                ->with('error', 'You are not authorize to create a course');
        }

        // Adding info for create page
        $page_info = [
            'title' => 'Add New Course',
            'back_button' => 'Back To Courses',
        ];

        return Inertia::render('Admin/Course/Create', [
            'page_info' => $page_info,
            'button_label' => __('buttons.courses.create'),
        ]);
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(StoreCourseRequest $request): RedirectResponse
    {
        // Check policy
        if (Gate::denies('create', Course::class)) {
            return redirect()->route('courses.index')
                ->with('error', 'You are not authorize to create a course');
        }

        // Course data is already validated and store course here
        $this->courseService->storeCourse($request->validated(), $request->file('image_path'));

        return redirect()->route('courses.index')
            ->with('success', 'Course has been created successfully.');
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course): RedirectResponse|InertiaResponse
    {
        // Uses the 'update' rule in CoursePolicy
        if (Gate::denies('update', $course)) {
            return redirect()->route('courses.index')
                ->with('error', 'You are not authorized to edit this course.');
        }

        // Adding info for edit page
        $page_info = [
            'title' => 'Edit Course',
            'back_button' => 'Back To Courses',
            'lesson_link' => '+ Add Lesson',
        ];

        // Eager load lessons and their creators to keep it fast
        $lessons = $course->lessons;

        // return edit page
        return Inertia::render('Admin/Course/Edit', [
            'course' => $course,
            'lessons' => $lessons,
            'page_info' => $page_info,
            'button_label' => __('buttons.courses.edit'),
        ]);
    }

    /**
     * Update the specified course in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course): RedirectResponse
    {
        // Check policy
        if (Gate::denies('update', $course)) {
            return redirect()->route('courses.index')
                ->with('error', 'You are not authorized to edit this course.');
        }

        // Course data is already validate and updating here
        $this->courseService->updateCourse($course, $request->validated(), $request->file('image_path'));

        return redirect()->route('courses.index', $course)
            ->with('success', 'Course has been updated successfully');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course): RedirectResponse
    {
        // Check policy
        if (Gate::denies('delete', $course)) {
            return redirect()->route('courses.index')
                ->with('error', 'You are not authorized to delete this course.');
        }

        try {
            $this->courseService->deleteCourse($course);

            return redirect()->route('courses.index')
                ->with('success', 'Course has been deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('courses.index')
                ->with('error', $e->getMessage());
        }
    }
}
