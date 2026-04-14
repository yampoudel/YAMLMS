<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user create/store the model 
     */
    public function create(User $authenticated_user)
    {
        //only admin can create/store the user
        return $authenticated_user->isAdmin();
    }


    /**
     * Determine whether the user update the model
     */
    public function update(User $authenticated_user, User $target_user)
    {
        //only admin can update the user
        return $authenticated_user->isAdmin();
    }

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
