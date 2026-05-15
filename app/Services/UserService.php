<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService
{
    /**
     * Get All users
     */
    public function getUserList($per_page = 15): LengthAwarePaginator
    {
        return User::paginate($per_page);
    }

    /**
     * Store user
     */
    public function storeUser(array $validated_user, ?UploadedFile $file = null): array
    {
        // Store plain password as needed later
        $plain_password = $validated_user['password'];

        // Handle profile picture if exists
        if ($file) {
            $validated_user['image_path'] = $file->store('user-images', 'public');
        }

        // Hash the password before saving for db
        $validated_user['password'] = Hash::make($validated_user['password']);

        // Deafult value
        $validated_user['join_date'] = now();
        $validated_user['last_login'] = null;

        // Create the user in the database
        $user = User::create($validated_user);

        // Return for welcome email
        return [
            'user' => $user,
            'password' => $plain_password,
        ];
    }

    /**
     * Update user
     */
    public function updateUser(User $user, array $validated_user, ?UploadedFile $file = null): User
    {
        // Check if a new file has been uploaded
        if ($file) {
            // Delete the old file from storage disk if it exists
            if ($validated_user['image_path']) {
                Storage::disk('public')->delete($validated_user['image_path']);
            }

            // Store the new file in the 'user-images' directory and save its path
            $validated_user['image_path'] = $file->store('user-images', 'public');
        }

        // Update in database lms_usertable
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
