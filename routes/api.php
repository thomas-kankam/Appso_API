<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Patient\PatientController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;

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

Route::middleware('auth:sanctum')->group(function () {
    // Verify email
    Route::post('doctor/email/verify', [RegisterController::class, 'verifyEmail']);
    Route::post('patient/email/verify', [RegisterController::class, 'verifyEmailPatient']);

    // CRUD routes for doctors and patients
    Route::apiResource('doctors', DoctorController::class);
    Route::apiResource('patients', PatientController::class);

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
