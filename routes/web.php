<?php

use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrolmentController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrainingRecordController;
use App\Http\Controllers\UserController;
use App\Models\Course;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $courses = Course::latest()->take(6)->get();

    // Send Course to the frontend to view and purchase
    return view('welcome', compact('courses'));
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Remove the standalone /dashboard and /profile groups
// and merge them into the main auth group for better readability.

Route::middleware(['auth', 'verified'])->group(function () {
    // Standard Dashboard

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Management
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // SHARED: User List & Courses
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('/check-login-uniqueness', [UserController::class, 'checkLoginUnique'])->name('users.checkLogin');
    Route::get('/check-email-uniqueness', [UserController::class, 'checkEmailUnique'])->name('users.checkEmail');

    Route::resource('courses', CourseController::class);

    // SHARED: Enrolment Module (Custom parameters)
    Route::resource('enrolments', EnrolmentController::class)->except(['create', 'store']);
    Route::get('enrolments/create/{user}', [EnrolmentController::class, 'create'])->name('enrolments.create');
    Route::post('enrolments/store/{user}', [EnrolmentController::class, 'store'])->name('enrolments.store');

    // Lessons Module
    Route::resource('lessons', LessonController::class);

    // Lesson progress module
    Route::get('courses/{course}/start', [LessonController::class, 'start'])->name('lessons.start');
    Route::get('learn/{course}/{lesson?}', [LessonController::class, 'play'])->name('lessons.play');
    Route::post('learn/{course}/lesson/{lesson}/complete', [LessonController::class, 'complete'])->name('lessons.complete');

    // Training record module
    Route::get('trainingrecord', [TrainingRecordController::class, 'index'])->name('trainingrecord.index');

    // Certificate Module
    Route::get('/course/{course}/certificate', [CertificateController::class, 'download'])->name('certificates.download');
});

// ADMIN ONLY: Protected by 'admin' middleware
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('users', UserController::class)->except(['index']);
});

require __DIR__.'/auth.php';
