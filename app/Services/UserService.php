<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService
{
    /**
     * Get all users with optional filters for role and status.
     */
    public function getUserList(int $per_page = 15, array $filters = []): LengthAwarePaginator
    {
        return User::query()
            ->when(! empty($filters['role']), function ($q) use ($filters) {
                $q->where('role', $filters['role']);
            })
            ->when(! empty($filters['status']), function ($q) use ($filters) {
                $q->where('status', $filters['status']);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($per_page)
            ->withQueryString();
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

        // Default value
        $validated_user['join_date'] = now();
        $validated_user['last_login'] = null;

        try {
            // Create the user normally
            $user = User::create($validated_user);
        } catch (UniqueConstraintViolationException $e) {
            // Safe fallback if duplicate request beats the insert
            $user = User::where('login', trim($validated_user['login']))->first();
        }

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
            // Check and delete from the existing $user record
            if ($user->image_path) {
                Storage::disk('public')->delete($user->image_path);
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
