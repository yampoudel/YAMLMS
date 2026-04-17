<?php

namespace App\Services;

use App\Models\Enrolment;
use Illuminate\Pagination\LengthAwarePaginator;

class EnrolmentService
{
    /**
     * Get the list of enrolments
     */
    public function getEnrolmentList($per_page = 15): LengthAwarePaginator
    {
        $user = auth()->user();

        $query = Enrolment::with(['user', 'course']);

        if ($user->isAdmin()) {
            return $query->latest()->paginate($per_page);
        }

        // Teacher: Filter by Course Ownership
        if ($user->role === 'Teacher') {
            return $query->whereHas('course', function ($q) use ($user) {
                $q->where('created_by', $user->id);
            })->latest()->paginate($per_page);
        }

        // Default: Return empty if role doesn't match
        return Enrolment::whereRaw('1=0')->paginate($per_page);
    }

    /**
     * Enroll User
     */
    public function enrollUser(int $user_id, int $course_id, int $enrolled_by)
    {
        // Check if the enrolment is already available
        if (Enrolment::alreadyExists($user_id, $course_id)) {
            throw new \Exception('This enrolment is already exists');
        }

        // Create enrolment
        Enrolment::create([
            'user_id' => $user_id,
            'course_id' => $course_id,
            'enrolled_at' => now(),
            'enrolled_by' => $enrolled_by,
        ]);
    }

    /**
     * Delete Enrolment
     */
    public function deleteEnrolment(Enrolment $enrolment)
    {
        // Check user either admin or not
        if (! auth()->user()->isAdmin()) {
            throw new \Exception('You donot have permission to delete enrolment');
        }

        return $enrolment->delete();
    }
}
