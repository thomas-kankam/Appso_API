<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function loginDoctor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse([
                'success' => false,
                'message' => $validator->errors(),
            ], 422);
        }

        $user = null;
        $pin = null;
        $email = $request->email;

        $user = Doctor::where('phone_number', $request->input('phone_number'))->first();

        // Check if user exists and if the password is correct
        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Invalid Credentials',
            ], 400);
        }

        // Generate a token for the authenticated user
        $token = $user ? $user->createToken('ApiToken_' . $user->first_name)->plainTextToken : null;

        return new JsonResponse([
            'success' => true,
            'token' => $token,
        ], 200);
    }


    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        return new JsonResponse([
            'success' => true,
            'message' => 'Logged Out Successfully',
        ], 200);
    }
}
