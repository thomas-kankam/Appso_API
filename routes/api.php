<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
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

Route::post('/register', [RegisterController::class, 'registerDoctor'])->name('register.doctor');

Route::match(['get', 'post'], '/login', [LoginController::class, 'loginDoctor'])->name('login.doctor');

Route::post('/resend/email/token', [RegisterController::class, 'resendPin'])->name('resendPin');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('email/verify', [RegisterController::class, 'verifyEmail']);
    Route::middleware('verify.api')->group(function () {
        Route::post('/logout', [LoginController::class, 'logout']);
    });
});

Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
Route::post('/verify/pin', [ForgotPasswordController::class, 'verifyPin']);
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword']);
