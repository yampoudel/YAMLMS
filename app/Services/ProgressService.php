<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseCompleted;
use App\Models\Lesson;
use App\Models\LessonCompleted;
use App\Models\User;

class ProgressService
{
    /**
     * Course start
     */
    public function startCourse(User $user, Course $course): void
    {
        // Initialize the Master Progress record
        CourseCompleted::firstOrCreate(
            ['user_id' => $user->id, 'course_id' => $course->id],
            [
                'status' => 'In Progress',
                'progress_percentage' => 0,
                'started_at' => now(),
            ]
        );
    }

    /**
     * Calculate Percentage
     */
    public function calculatePercentage(User $user, Course $course): int
    {
        $total = $course->lessons()->count();

        // if there are no lessons in that course
        if ($total === 0) {
            return 0;
        }

        $completed = LessonCompleted::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->count();

        return (int) round(($completed / $total) * 100);
    }

    /**
     * Update course progress
     */
    public function updateCourseProgress(User $user, Course $course): void
    {
        $new_percentage = $this->calculatePercentage($user, $course);

        // Update course progress table with new percentage and database sync
        CourseCompleted::updateOrCreate(
            ['user_id' => $user->id, 'course_id' => $course->id],
            [
                'status' => ($new_percentage < 100) ? 'In Progress' : 'Completed',
                'progress_percentage' => $new_percentage,
                'completed_at' => $new_percentage === 100 ? now() : null,
            ]
        );
    }

    /**
     * Mark a lesson as finished and trigger progress updates.
     */
    public function completeLesson(User $user, Course $course, Lesson $lesson): void
    {
        // Mark the lesson as completed for the user and course
        LessonCompleted::firstOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            [
                'course_id' => $course->id,
                'completed_at' => now(),
            ]
        );

        // Update the course progress after marking the lesson as completed
        $this->updateCourseProgress($user, $course);
    }
}
