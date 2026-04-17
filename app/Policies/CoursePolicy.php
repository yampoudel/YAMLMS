<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $authenticated_user, Course $target_course): bool
    {
        // Check admin status
        if ($authenticated_user->isAdmin()) {
            return true;
        }

        // Check teacher status
        if ($authenticated_user->role === 'Teacher' && $authenticated_user->id === $target_course->created_by) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $authenticated_user, Course $target_course): bool
    {
        // Only admin user can delete the course
        return $authenticated_user->isAdmin();
    }
}
