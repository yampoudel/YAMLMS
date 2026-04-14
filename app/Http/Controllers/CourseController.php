<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Services\CourseService;
use Illuminate\Support\Facades\Gate;

class CourseController extends Controller
{
    /**
     * Dependency Injection from CourseService
     */
    public function __construct(protected CourseService $courseService) { }

    /**
     * Display a listing of the course.
     */
    public function index(): View
    {
        $courses = $this->courseService->getAllCourses(15);

        return  view('admin.course.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course
     */
    public function create(): View
    {
        $page_info = [];
        $page_info['title']= 'Add a new course';

        return view('admin.course.create', compact('page_info'));
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(StoreCourseRequest $request): RedirectResponse
    {
        //Course data is already validated and store course here
        $this->courseService->storeCourse($request->validated());
    
        return redirect()->route('courses.index')
                         ->with('success', 'Course has been created successfully.');
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course): View | RedirectResponse
    {   
        // Uses the 'update' rule in CoursePolicy
        if (Gate::denies('update', $course)) {
            return redirect()->route('courses.index')
                             ->with('error', 'You are not authorized to edit this course.');
        }
        
        //return edit page
        return view ('admin.course.edit', compact('course'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course): RedirectResponse
    {   
        if (Gate::denies('update', $course)) {
            return redirect()->route('courses.index')
                             ->with('error', 'You are not authorized to edit this course.');
        }

        //Course data is already validate and updating here
        $this->courseService->updateCourse($course, $request->validated());

        return redirect()->route('courses.edit', $course)
                         ->with('success', 'Course has been updated successfully');    
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course): RedirectResponse
    {
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
