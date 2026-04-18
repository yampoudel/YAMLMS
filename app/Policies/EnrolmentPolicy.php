<?php

namespace App\Policies;

use App\Models\Enrolment;
use App\Models\User;

class EnrolmentPolicy
{
    /**
     * Determine whether the user can view enrolments
     */
    public function viewAny(User $authenticated_user): bool
    {
        return $authenticated_user->isAdmin() || $authenticated_user->role === 'Teacher';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $authenticated_user, User $targeted_user): bool
    {
        if ($authenticated_user->isAdmin()) {
            return true;
        }

        // Teacher specific rules
        if ($authenticated_user->role === 'Teacher') {

            // Teachers can enrol to only learners(no admins, no other teachers or themselves)
            return ! in_array($targeted_user->role, ['Admin', 'Teacher'])
                && $authenticated_user->id !== $targeted_user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $authenticated_user, Enrolment $enrolment): bool
    {
        // Admin can delete all enrolments
        return $authenticated_user->isAdmin();
    }
}
