<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Pagination\LengthAwarePaginator;

class CourseService
{
    /**
     * Get all courses
     */
    public function getAllCourses(int $per_page): LengthAwarePaginator
    {
        return Course::paginate($per_page);
    }

    /**
     * Store user
     */
    public function storeCourse(array $validated_course): Course
    {
        //Adding course created Id
        $validated_course['created_by'] = auth()->id();

        //Add course to the table
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