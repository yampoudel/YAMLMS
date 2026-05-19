<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\Integrations\StripeWebhookController;
use Illuminate\Support\Facades\Route;

/*

|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------

|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will

| be assigned to the "api" middleware group. Make something great!
|
*/

/*

|--------------------------------------------------------------------------
| Core Internal LMS Endpoints (For Frontend / Mobile App)
|--------------------------------------------------------------------------
*/

// --- 1. Public Auth Routes ---
Route::post('/login', [AuthController::class, 'login']);

// --- 2. Protected Routes ---
Route::middleware('auth:sanctum')->group(function () {

    // User Management
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Course Data
    Route::get('/courses', [CourseController::class, 'index']);

    // User Activity
    Route::get('/courses/{course}', [CourseController::class, 'show']);
    Route::get('/my-courses', [CourseController::class, 'myCourses']);
    Route::get('/my-progress', [CourseController::class, 'progress']);
});

/*

|--------------------------------------------------------------------------
| Third-Party Integrations & Webhooks
|--------------------------------------------------------------------------
| URL Prefix: /api/integrations/...
*/
Route::prefix('integrations')->group(function () {
    // Stripe Sub-group
    Route::prefix('stripe')->group(function () {
        // Webhook endpoint (Public - No auth middleware)
        Route::post('/webhook', [StripeWebhookController::class, 'handleWebhook']);

        // Checkout intent creation endpoint (Protected - Requires user login token)
        Route::post('/intent/{course}', [StripeCheckoutController::class, 'createIntent'])->middleware('auth:sanctum');
    });
});
