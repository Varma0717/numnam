<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Models\User;
use App\Support\JwtService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class MobileAuthController extends BaseMobileController
{
    private JwtService $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $this->jwtService->issueToken($user);

        return $this->success([
            'token_type' => 'Bearer',
            'access_token' => $token,
            'expires_in_minutes' => (int) config('jwt.ttl_minutes', 60),
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ], 'Registration successful.', 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $this->jwtService->issueToken($user);

        return $this->success([
            'token_type' => 'Bearer',
            'access_token' => $token,
            'expires_in_minutes' => (int) config('jwt.ttl_minutes', 60),
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ], 'Login successful.');
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->success([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ], 'Authenticated user profile.');
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:users,email,' . $user->id,
        ]);

        if (array_key_exists('name', $validated)) {
            $user->name = $validated['name'];
        }

        if (array_key_exists('email', $validated)) {
            $user->email = $validated['email'];
        }

        if ($user->isDirty()) {
            $user->save();
        }

        return $this->success([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ], 'Profile updated successfully.');
    }

    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();
        $token = $this->jwtService->issueToken($user);

        return $this->success([
            'token_type' => 'Bearer',
            'access_token' => $token,
            'expires_in_minutes' => (int) config('jwt.ttl_minutes', 60),
        ], 'Token refreshed successfully.');
    }
}
