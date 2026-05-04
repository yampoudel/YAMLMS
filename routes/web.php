<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrolmentController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\Course;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $courses = Course::latest()->take(6)->get();

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
});

// ADMIN ONLY: Protected by 'admin' middleware
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('users', UserController::class)->except(['index']);
});

// Send Course to the frontend to view and purchase

require __DIR__.'/auth.php';
