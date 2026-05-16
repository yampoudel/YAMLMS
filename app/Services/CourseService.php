<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class CourseService
{
    /**
     * Get all courses based on user
     */
    public function getCourseList(int $per_page): LengthAwarePaginator
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
    public function storeCourse(array $validated_course, ?UploadedFile $file = null): Course
    {
        // Adding course created Id
        $validated_course['created_by'] = auth()->id();

        // Handle file if exists
        if ($file) {
            $validated_course['image_path'] = $file->store('course-images', 'public');
        }

        // Add course to the table
        return Course::create($validated_course);
    }

    /**
     * Update course
     */
    public function updateCourse(Course $course, array $validated_course, ?UploadedFile $file = null): Course
    {
        // Check if new file has been uploaded
        if ($file) {
            // Check and delete from the existing $course record
            if ($course->image_path) {
                Storage::disk('public')->delete($course->image_path);
            }

            // Store the new file in the 'course-images' directory and save its path
            $validated_course['image_path'] = $file->store('course-images', 'public');
        }

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
