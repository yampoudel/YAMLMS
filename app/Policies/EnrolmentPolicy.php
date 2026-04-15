<?php

namespace App\Policies;

use App\Models\Enrolment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EnrolmentPolicy
{
    /**
     * Determine whether the user can view enrolments
     */
    public function view(User $authenticated_user): bool
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
    
        if ($authenticated_user->role === 'Teacher') {
            // Must NOT be an Admin AND must NOT be themselves
            return $targeted_user->role !== 'Admin' && $authenticated_user->id !== $targeted_user->id; 
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $authenticated_user, Enrolment $enrolment): bool
    {
        //Admin can delete all enrolments
        return $authenticated_user->isAdmin();
    }
}
