<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;

class CourseController extends Controller
{
    /**
     * Display a listing of the course.
     */
    public function index(): View
    {
        $courses = Course::paginate(15);

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
        //Course data is already validated
        $validated_course = $request->validated();
  
        //Adding course created Id
        $validated_course['created_by'] = auth()->id();

        //Add course to the table
        Course::create($validated_course);

        //redirect to the index page
        return redirect()->route('courses.index')->with('success', 'Course has been created successfully.');
    }

    /**
     * Display the specified Course.
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course): View
    {
        //return edit page
        return view ('admin.course.edit', compact('course'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course): RedirectResponse
    {
        //will add/edit updated_by in future if required

        //updating the user
        $course->update($request->validated());

        return redirect()->route('courses.edit', $course)->with('success', 'Course has been updated successfully');    
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course): RedirectResponse
    {
        //Some permission to delete will be added in the future
        
        //Delete Course
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course has been deleted successfully');
    }
}
