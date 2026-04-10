<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController; 
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrolmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//User module routes creates all route for crud operation
Route::resource('users', Usercontroller::class);

//Course module routes
Route::resource('courses', CourseController::class);

//Enrolment module routes
Route::resource('enrolments', EnrolmentController::class)->except(['create', 'store']);
Route::get('enrolments/create/{user}', [EnrolmentController::class, 'create'])-> name('enrolments.create');
Route::post('enrolments/store/{user}', [EnrolmentController::class, 'store'])->name('enrolments.store');

require __DIR__.'/auth.php';
