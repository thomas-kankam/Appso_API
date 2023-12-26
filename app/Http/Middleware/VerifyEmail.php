<?php

namespace App\Http\Middleware;

use App\Models\Doctor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class VerifyEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $doctor = $request->user();

        if ($doctor && $doctor->email_verified_at === null) {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => 'Please verify your email before you can continue'
                ],
                401
            );
        }

        return $next($request);
    }
}
