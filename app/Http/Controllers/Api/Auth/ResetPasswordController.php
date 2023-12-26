<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = null;

        if ($validator->fails()) {
            return new JsonResponse(['success' => false, 'message' => $validator->errors()], 422);
        }

        $user = Doctor::where('email', $request->input('email'))->first();

        if (!$user) {
            return new JsonResponse(['success' => false, 'message' => 'User not found'], 404);
        }

        $user->update([
            'password' => Hash::make($request->input('password'))
        ]);

        // Generate a token for the authenticated user
        $token = $user ? $user->createToken('ApiToken_' . $user->first_name)->plainTextToken : null;

        return new JsonResponse([
            'success' => true,
            'message' => 'Your password has been reset',
            'token' => $token
        ], 200);
    }
}
