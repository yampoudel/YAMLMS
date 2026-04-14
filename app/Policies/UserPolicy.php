<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $authenticated_user, User $target_user): bool
    {
        //Only admin can delete any one
        if (!$authenticated_user->isAdmin()) {
            return false;
        }
        
        //Admin cannot delete themselves
        return $authenticated_user->id !== $target_user->id;  
    }
}
