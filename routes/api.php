<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Like\LikeController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Reply\ReplyController;
use App\Http\Controllers\Route\RouteController;
use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\Rating\RatingController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Comment\CommentController;
use App\Http\Controllers\Patient\PatientController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\PostCategory\PostCategoryController;

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

// Register routes
Route::post('/doctor/register', [RegisterController::class, 'registerDoctor'])->name('register.doctor');
Route::post('/patient/register', [RegisterController::class, 'registerPatient'])->name('register.patient');

// Login routes
Route::match(['get', 'post'], '/doctor/login', [LoginController::class, 'loginDoctor'])->name('login.doctor');
Route::match(['get', 'post'], '/patient/login', [LoginController::class, 'loginPatient'])->name('login.patient');

// Resend email token
Route::post('/resend/email/token', [RegisterController::class, 'resendPin'])->name('resendPin');

// Verify email
Route::post('doctor/email/verify', [RegisterController::class, 'verifyEmail']);
Route::post('patient/email/verify', [RegisterController::class, 'verifyEmailPatient']);

Route::middleware('auth:sanctum')->group(function () {

    // CRUD routes for doctors and patients
    Route::apiResource('doctors', DoctorController::class)->only(['index', 'show', 'destroy']);
    Route::apiResource('patients', PatientController::class)->only(['index', 'show', 'destroy']);

    // Rating routes
    // Route::get('/ratings/create/{doctorId}', [RatingController::class, 'create']);
    // Route::post('/ratings/store/{doctorId}', [RatingController::class, 'store'])->name('ratings.store');

    // comments routes
    Route::apiResource('comments', CommentController::class)->only(['index', 'store', 'update', 'destroy']);

    // Like routes
    Route::apiResource('like', LikeController::class)->only(['index', 'store', 'update', 'destroy']);

    // Post routes
    Route::apiResource('post', PostController::class)->except(['show']);

    // Post Category routes
    Route::apiResource('postCategory', PostCategoryController::class)->except(['show']);

    // Reply routes
    Route::apiResource('reply', ReplyController::class)->except(['show']);

    // Logout route
    Route::middleware('verify.api')->group(function () {
        Route::post('/logout', [LoginController::class, 'logout']);
    });
});

// Password reset routes
Route::post('doctor/forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
Route::post('patient/forgot-password', [ForgotPasswordController::class, 'forgotPasswordPatient']);

// Verify pin
Route::post('/verify/pin', [ForgotPasswordController::class, 'verifyPin']);

// Reset password
Route::post('doctor/reset-password', [ResetPasswordController::class, 'resetPassword']);
Route::post('patient/reset-password', [ResetPasswordController::class, 'resetPasswordPatient']);
