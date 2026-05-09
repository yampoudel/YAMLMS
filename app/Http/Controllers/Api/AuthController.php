<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle user login and provide api token
     */
    public function login(Request $request): JsonResponse
    {
        // Validate the request
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'device_name' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)->first();

        // Check Credentials
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials do not match our records.'],
            ]);
        }

        // Get the name from the request, or fallback to the browser name
        $device_name = $request->input('device_name', $request->userAgent() ?? 'Unknown Device');

        // Create and return plain text token
        return response()->json([
            'token' => $user->createToken($device_name)->plainTextToken,
            'user' => $user,
        ], 200); // 200 ok
    }

    /**
     * Revoke current access token
     */
    public function logout(Request $request): JsonResponse
    {
        // Revoke the token provide
        $request->user()->tokens()
            ->where('id', $request->user()->currentAccessToken()->id)
            ->delete();

        return response()->json(['message' => 'Logged Out Successfully'], 200); // 200 ok
    }
}
