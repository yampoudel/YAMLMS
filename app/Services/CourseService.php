<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class CourseService
{
    /**
     * Get all courses based on user and search filters.
     */
    public function getCourseList(int $per_page, array $filters = []): LengthAwarePaginator
    {
        $user = auth()->user();

        // Set up the correct base query depending on user role
        if ($user->isAdmin()) {
            $query = Course::query();
        } elseif ($user->role === 'Teacher') {
            $query = Course::where('created_by', $user->id);
        } else {
            // If they are neither, return an empty query safely
            $query = Course::whereRaw('1 = 0');
        }

        // Apply search filters to the query builder
        $query->when(! empty($filters['title']), function ($q) use ($filters) {
            $q->where('title', 'like', '%'.$filters['title'].'%');
        });

        $query->when(! empty($filters['status']), function ($q) use ($filters) {
            $q->where('status', $filters['status']);
        });

        // Apply sorting and pagination with query parameters preserved
        return $query->orderBy('created_at', 'desc')
            ->paginate($per_page)
            ->withQueryString();
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
