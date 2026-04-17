<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Get All users
     */
    public function getAllUsers($per_page = 15): LengthAwarePaginator
    {
        return User::paginate($per_page);
    }

    /**
     * Store user
     */
    public function storeUser(array $validated_user): User
    {
        // Hash the password before saving!
        $validated_user['password'] = Hash::make($validated_user['password']);

        // Deafult value
        $validated_user['join_date'] = now();
        $validated_user['last_login'] = null;

        // Create the user in the database
        return User::create($validated_user);
    }

    /**
     * Update user
     */
    public function updateUser(User $user, array $validated_user): User
    {
        $user->update($validated_user);

        return $user;
    }

    /**
     * Delete user
     */
    public function deleteUser(User $user): bool
    {
        // Prevent deleting own account
        if (auth()->id() === $user->id) {
            throw new \Exception('You cannot delete your own account');
        }

        return $user->delete();
    }
}
