<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /** POST /api/v1/auth/register */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:15',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('numnam-app')->plainTextToken;

        return response()->json([
            'user'  => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => (bool) $user->is_admin,
            ],
            'token' => $token,
        ], 201);
    }

    /** POST /api/v1/auth/login */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Revoke old tokens (single session); remove this line for multi-device
        $user->tokens()->delete();

        $token = $user->createToken('numnam-app')->plainTextToken;

        return response()->json([
            'user'  => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => (bool) $user->is_admin,
            ],
            'token' => $token,
        ]);
    }

    /** POST /api/v1/auth/logout */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    /** GET /api/v1/auth/me */
    public function me(Request $request): JsonResponse
    {
        return response()->json(['data' => $request->user()]);
    }
}
