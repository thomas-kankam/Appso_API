<?php

namespace App\Http\Controllers\Api\Auth;

use Carbon\Carbon;
use App\Models\Doctor;
use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Doctor\RegistrationRequest;
use App\Models\Patient;

class RegisterController extends Controller
{
    public function registerDoctor(RegistrationRequest $request)
    {
        $request->validated();

        $national_id_front_image = $request->file('national_id_front_image')->store('public/images');
        $national_id_back_image = $request->file('national_id_back_image')->store('public/images');
        $passport_picture = $request->file('passport_picture')->store('public/images');

        $doctor = null;
        $pin = null;
        $email = $request->email;

        // Use a database transaction for atomicity
        DB::transaction(function () use ($request, &$doctor, $email, &$pin, $national_id_front_image, $national_id_back_image, $passport_picture) {
            $doctor = Doctor::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'role' => $request->role,
                'email' => $email,
                'phone_number' => $request->phone_number,
                'hospital_name' => $request->hospital_name,
                'national_id' => $request->national_id,
                'national_id_front_image' => $national_id_front_image,
                'national_id_back_image' => $national_id_back_image,
                'passport_picture' => $passport_picture,
                "password" => Hash::make($request->password),
            ]);

            if ($doctor) {
                $verify2 =  DB::table('password_reset_tokens')->where([
                    ['email', $email]
                ]);

                if ($verify2->exists()) {
                    $verify2->delete();
                }

                $pin = rand(100000, 999999);

                // Include an expiration time for the verification token
                DB::table('password_reset_tokens')->insert([
                    'email' => $email,
                    'token' => $pin,
                    'created_at' => now(),
                    'expires_at' => now()->addMinutes(30),
                ]);
            }
        });

        // Send a verification email with the pin
        Mail::to($email)->send(new VerifyEmail($pin));

        // Generate a token for the newly registered doctor
        $token = $doctor ? $doctor->createToken('ApiToken_' . $doctor->first_name)->plainTextToken : null;

        return response()->json([
            'success' => true,
            'message' => 'Successful created doctor. Please check your email for a 6-digit pin to verify your email.',
            'data' => $doctor,
            'token' => $token,
            'status' => 201
        ]);
    }


    public function registerPatient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:patients'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['success' => false, 'message' => $validator->errors()], 400);
        }

        $patient = null;
        $pin = null;
        $email = $request->email;

        // Use a database transaction for atomicity
        DB::transaction(function () use ($request, &$patient, $email, &$pin) {
            $patient = Patient::create([
                'full_name' => $request->full_name,
                'phone_number' => $request->phone_number,
                'email' => $email,
                "password" => Hash::make($request->password),
            ]);

            if ($patient) {
                $verify2 =  DB::table('password_reset_tokens')->where([
                    ['email', $email]
                ]);

                if ($verify2->exists()) {
                    $verify2->delete();
                }

                $pin = rand(100000, 999999);

                // Include an expiration time for the verification token
                DB::table('password_reset_tokens')->insert([
                    'email' => $email,
                    'token' => $pin,
                    'created_at' => now(),
                    'expires_at' => now()->addMinutes(30),
                ]);
            }
        });

        // Send a verification email with the pin
        Mail::to($email)->send(new VerifyEmail($pin));

        // Generate a token for the newly registered patient
        $token = $patient ? $patient->createToken('ApiToken_' . $patient->full_name)->plainTextToken : null;

        return response()->json([
            'success' => true,
            'message' => 'Successful created patient. Please check your email for a 6-digit pin to verify your email.',
            'data' => $patient,
            'token' => $token,
            'status' => 201
        ]);
    }


    public function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => ['required'],
            'email' => ['required', 'exists:doctors,email'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['success' => false, 'message' => $validator->errors()], 400);
        }

        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$tokenRecord) {
            return new JsonResponse(['success' => false, 'message' => "Invalid PIN"], 400);
        }

        // Delete the token record after verifying
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->delete();

        // Update the user's email_verified_at timestamp
        $user = Doctor::where('email', $request->email)->first();

        if ($user) {
            $user->email_verified_at = now();
            $user->is_verified = true;
            $user->save();

            return new JsonResponse(['success' => true, 'message' => "Email is verified"], 200);
        } else {
            return new JsonResponse(['success' => false, 'message' => "User not found"], 404);
        }
    }


    public function verifyEmailPatient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => ['required'],
            'email' => ['required', 'exists:patients,email'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['success' => false, 'message' => $validator->errors()], 400);
        }

        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$tokenRecord) {
            return new JsonResponse(['success' => false, 'message' => "Invalid PIN"], 400);
        }

        // Delete the token record after verifying
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->delete();

        // Update the user's email_verified_at timestamp
        $user = Patient::where('email', $request->email)->first();

        if ($user) {
            $user->email_verified_at = now();
            $user->is_verified = true;
            $user->save();

            return new JsonResponse(['success' => true, 'message' => "Email is verified"], 200);
        } else {
            return new JsonResponse(['success' => false, 'message' => "User not found"], 404);
        }
    }


    public function resendPin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['success' => false, 'message' => $validator->errors()], 422);
        }

        // Delete any existing password reset records for the email
        DB::table('password_reset_tokens')->where('email', $request->input('email'))->delete();

        // Generate a new verification token
        $token = random_int(100000, 999999);

        // Set the expiration time (e.g., 30 minutes from now)
        $expirationTime = now()->addMinutes(30);

        // Insert the new token record with expiration time
        $password_reset = DB::table('password_reset_tokens')->insert([
            'email' => $request->input('email'),
            'token' => $token,
            'expires_at' => $expirationTime,
            'created_at' => now(),
        ]);

        if ($password_reset) {
            // Send a verification email with the new token
            Mail::to($request->input('email'))->send(new VerifyEmail($token));

            return new JsonResponse([
                'success' => true,
                'message' => 'A verification mail has been resent. Token expires at ' . $expirationTime,
            ], 200);
        }
    }
}
