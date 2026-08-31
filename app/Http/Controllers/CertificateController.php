<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CertificateController extends Controller
{
    // Display Certificate View
    public function view(Course $course, Request $request)
    {
        $targetUserId = $request->get('user_id') ?? auth()->id();
        $targetUser = User::findOrFail($targetUserId);

        // Security Validation Checks
        $currentUser = auth()->user();
        $hasAccessPermission = $currentUser->isAdmin()
            || $currentUser->isTeacher()
            || $currentUser->id === (int) $targetUserId;

        if (! $hasAccessPermission) {
            abort(403, 'You do not have permission to view this certificate.');
        }

        // Verify course completion
        $progress = $targetUser->courseProgress()->where('course_id', $course->id)->first();
        if (! $progress || $progress->progress_percentage < 100) {
            return back()->with('error', 'Certificate is locked until 100% course completion.');
        }

        return Inertia::render('CertificatePage', [
            'certData' => [
                'name' => $targetUser->name,
                'course' => $course->title,
                'date' => $progress->completed_at ? $progress->completed_at->format('d M, Y') : now()->format('d M, Y'),
                'certificateId' => 'YAM-'.$course->id.'-'.$targetUser->id,
            ],
            'queryParams' => [
                'course_id' => $course->id,
                'user_id' => $targetUser->id,
            ],
        ]);
    }
}
