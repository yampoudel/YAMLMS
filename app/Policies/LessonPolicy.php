<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;

class LessonPolicy
{
    /**
     * Determine whether the user can view lessons list.
     */
    public function viewAny(User $authenticated_user): bool
    {
        return $authenticated_user->isAdmin() || $authenticated_user->role === 'Teacher';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $authenticated_user): bool
    {
        // Admin can always create lessons
        if (auth()->user()->isAdmin()) {
            return true;
        }

        // Teacher only create lesson if having own at least one course
        if ($authenticated_user->role === 'Teacher') {
            return Course::where('created_by', $authenticated_user->id)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $authenticated_user, Lesson $target_lesson): bool
    {
        // Check admin status
        if ($authenticated_user->isAdmin()) {
            return true;
        }

        // Check teacher status and ownership of this specific lesson
        if ($authenticated_user->role === 'Teacher' && $authenticated_user->id === $target_lesson->created_by) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $authenticated_user, Lesson $target_lesson): bool
    {
        // Admin can delete any lesson
        if ($authenticated_user->isAdmin()) {
            return true;
        }

        // Check teacher status and ownership of this specific lesson
        if ($authenticated_user->role === 'Teacher' && $authenticated_user->id === $target_lesson->created_by) {
            return true;
        }

        return false;
    }
}
