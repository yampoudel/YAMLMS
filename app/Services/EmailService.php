<?php

namespace App\Services;

use App\Mail\CourseCompletedEmail;
use App\Mail\NewUserWelcomeEmail;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    /**
     * Send welcome email to a newly created user.
     */
    public function sendWelcomeEmail(User $user, string $password): void
    {
        // This tells the lms_jobs table: "Wait 2 minutes before showing this to the worker"
        Mail::to($user->email)
            ->later(now()->addMinutes(2), new NewUserWelcomeEmail($user, $password));
    }

    /**
     * Send email to learner when the course is completed
     */
    public function sendCourseCompletedEmail(User $user, Course $course): void
    {
        // Sends email immediately after completing the course
        Mail::to($user->email)
            ->queue(new CourseCompletedEmail($user, $course));
    }
}
