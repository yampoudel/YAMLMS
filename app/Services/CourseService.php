<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Pagination\LengthAwarePaginator;

class CourseService
{
    /**
     * Get all courses based on user
     */
    public function getAllCourses(int $per_page): LengthAwarePaginator
    {
        $user = auth()->user();

        // Only admin can see all the courses
        if (auth()->user()->isAdmin()) {
            return Course::paginate($per_page);
        }

        // If teacher will see the courses which are created by themselves
        if (auth()->user()->role === 'Teacher') {
            return Course::where('created_by', $user->id)->paginate($per_page);
        }

        // Default: Return an empty paginator or throw an exception
        return Course::where('id', 0)->paginate($per_page);
    }

    /**
     * Store user
     */
    public function storeCourse(array $validated_course): Course
    {
        // Adding course created Id
        $validated_course['created_by'] = auth()->id();

        // Add course to the table
        return Course::create($validated_course);
    }

    /**
     * Update course
     */
    public function updateCourse(Course $course, array $validated_course): Course
    {
        $course->update($validated_course);

        return $course;
    }

    /**
     * Delete course
     */
    public function deleteCourse(Course $course): bool
    {
        return $course->delete();
    }
}
