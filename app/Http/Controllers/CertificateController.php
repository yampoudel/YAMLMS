<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function download(Course $course, Request $request)
    {
        // Get Target User
        $user_id = $request->get('user_id') ?? auth()->id();
        $user = User::findOrFail($user_id);

        // Security Check: Check the LOGGED IN user's permissions
        $me = auth()->user();
        $can_access = $me->isAdmin()
            || $me->isTeacher()
            || $me->id === (int) $user_id; // Check if I am downloading my own

        if (! $can_access) {
            abort(403, 'You do not have permission to download this certificate.');
        }

        // Progress Check
        $progress = $user->courseProgress()->where('course_id', $course->id)->first();

        if (! $progress || $progress->progress_percentage < 100) {
            return back()->with('error', 'Certificate is locked until 100% completion.');
        }

        // Prepare Data
        $data = [
            'name' => $user->name,
            'course' => $course->title,
            // Ensure $casts = ['completed_at' => 'datetime'] is in your CourseProgress model!
            'date' => $progress->completed_at ? $progress->completed_at->format('d M, Y') : now()->format('d M, Y'),
            'certificate_id' => 'YAM-'.$course->id.'-'.$user->id,
        ];

        // Generate and Download
        $pdf = Pdf::loadView('certificate.standard', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->download("Certificate-{$user->name}.pdf");
    }
}
