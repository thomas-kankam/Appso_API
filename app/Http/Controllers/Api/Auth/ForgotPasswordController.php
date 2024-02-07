<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\ResetPassword;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['success' => false, 'message' => $validator->errors(), 'status' => 422]);
        }

        $email = $request->input('email');

        // Check if the doctor with the given email exists
        if (!Doctor::where('email', $email)->exists()) {
            return new JsonResponse(['success' => false, 'message' => "This email does not exist", 'status' => 400]);
        }

        // Delete any existing password reset tokens for the email
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        // Generate a new password reset token
        $token = random_int(100000, 999999);

        // Insert the new token record with expiration time
        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => now(),
            'expires_at' => now()->addMinutes(30),
        ]);

        // Send a password reset email with the new token
        Mail::to($email)->send(new ResetPassword($token));

        return new JsonResponse([
            'success' => true,
            'message' => "Please check your email for a 6-digit pin",
            'status' => 200
        ]);
    }


    public function forgotPasswordPatient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['success' => false, 'message' => $validator->errors()], 422);
        }

        $email = $request->input('email');

        // Check if the doctor with the given email exists
        if (!Patient::where('email', $email)->exists()) {
            return new JsonResponse(['success' => false, 'message' => "This email does not exist"], 400);
        }

        // Delete any existing password reset tokens for the email
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        // Generate a new password reset token
        $token = random_int(100000, 999999);

        // Insert the new token record with expiration time
        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => now(),
            'expires_at' => now()->addMinutes(30),
        ]);

        // Send a password reset email with the new token
        Mail::to($email)->send(new ResetPassword($token));

        return new JsonResponse([
            'success' => true,
            'message' => "Please check your email for a 6-digit pin",
        ], 200);
    }

    public function verifyPin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'token' => ['required'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['success' => false, 'message' => $validator->errors(), 'status' => 422]);
        }

        $check = DB::table('password_reset_tokens')
            ->where('email', $request->input('email'))
            ->where('token', $request->input('token'));

        if ($check->exists()) {
            $createdTime = $check->first()->created_at;
            $difference = Carbon::now()->diffInSeconds($createdTime);

            if ($difference > 3600) {
                // Token expired
                $check->delete();
                return new JsonResponse(['success' => false, 'message' => 'Token Expired', 'status' => 400]);
            }

            // Token is valid, delete it
            $check->delete();

            return new JsonResponse([
                'success' => true,
                'message' => 'You can now reset your password',
                'status' => 200
            ]);
        }

        return new JsonResponse([
            'success' => false,
            'message' => 'Invalid token',
            'status' => 401
        ]);
    }
}
