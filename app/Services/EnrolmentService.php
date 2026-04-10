<?php

namespace App\Services;

use App\Models\Enrolment;
use Illuminate\Pagination\LengthAwarePaginator;

class EnrolmentService
 {
    /**
     * Get the list of enrolments
     */
    public function getEnrolmentList($perPage = 15): LengthAwarePaginator 
    {
       return  Enrolment::with(['user', 'course'])->paginate($perPage);
    }

    /**
     * Enroll User
     */
    public function enrollUser(int $user_id, int $course_id, int $enrolled_by)
    {
      //Check if the enrolment is already available
      if (Enrolment::alreadyExists($user_id, $course_id)) {
        throw new \Exception('This enrolment is already exists');
      }

      //Create enrolment
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
      //Check user either admin or not
      if(!auth()->user()->isAdmin()) {
        throw new \Exception('You donot have permission to delete enrolment');
      }

      return $enrolment->delete();
      
    }
}