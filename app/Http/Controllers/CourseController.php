<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $courses = Course::paginate(15);

        return  view('admin.course.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resourse/course
     */
    public function create(): View
    {
        $page_info = [];
        $page_info['title']= 'Add a new course';

        return view('admin.course.create', compact('page_info'));
    }

    /**
     * Store a newly created resource/course in storage.
     */
    public function store(Request $request): RedirectResponse
    {
      //validate incoming data
        $validated_course = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string'
        ]);
  
        //Adding course created Id
        $validated_course['created_by'] = auth()->id();

        //Add course to the table
        Course::create($validated_course);

        //redirect to the index page
        return redirect()->route('courses.index')->with('success', 'Course has been created successfully.');
    }

    /**
     * Display the specified resource/Course.
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource/Course.
     */
    public function edit(Course $course): View
    {
        //return edit page
        return view ('admin.course.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course): RedirectResponse
    {
        //validating incoming data for update
        $validated_course = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        //will add/edit updated_by in future if required

        //updating the user
        $course->update($validated_course);

        return redirect()->route('courses.edit', $course)->with('success', 'Course has been updated successfully');    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course): RedirectResponse
    {
        //Some permission to delete will be added in the future
    
        //Delete Course
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course has been deleted successfully');
    }
}
