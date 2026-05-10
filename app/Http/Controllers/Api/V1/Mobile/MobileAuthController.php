<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Models\User;
use App\Support\JwtService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
            'user' => $this->userProfile($user),
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
            'user' => $this->userProfile($user),
        ], 'Login successful.');
    }

    public function me(Request $request): JsonResponse
    {
        return $this->success($this->userProfile($request->user()), 'Authenticated user profile.');
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'sometimes|nullable|string|max:20',
            'date_of_birth' => 'sometimes|nullable|date',
            'gender' => 'sometimes|nullable|string|in:male,female,other',
            'address_line1' => 'sometimes|nullable|string|max:255',
            'address_line2' => 'sometimes|nullable|string|max:255',
            'city' => 'sometimes|nullable|string|max:100',
            'state' => 'sometimes|nullable|string|max:100',
            'postal_code' => 'sometimes|nullable|string|max:20',
            'country' => 'sometimes|nullable|string|max:100',
        ]);

        $user->fill($validated);

        if ($user->isDirty()) {
            $user->save();
        }

        return $this->success($this->userProfile($user), 'Profile updated successfully.');
    }

    public function uploadAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => 'required|image|max:2048|mimes:jpg,jpeg,png,webp',
        ]);

        $user = $request->user();

        // Delete old avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $path;
        $user->save();

        return $this->success($this->userProfile($user), 'Avatar updated.');
    }

    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = $request->user();

        if (!Hash::check($request->input('current_password'), $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Current password is incorrect.'],
            ]);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return $this->success(null, 'Password changed successfully.');
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            // Return success even if user not found (prevent email enumeration)
            return $this->success(null, 'If that email exists, a reset code has been sent.');
        }

        // Generate a 6-digit OTP
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store hashed token (email is primary key, so upsert)
        DB::table('password_resets')->updateOrInsert(
            ['email' => $user->email],
            ['token' => Hash::make($code), 'created_at' => now()]
        );

        // Send email with the code
        try {
            Mail::raw(
                "Your NumNam password reset code is: {$code}\n\nThis code expires in 30 minutes.\n\nIf you did not request this, please ignore this email.",
                function ($message) use ($user) {
                    $message->to($user->email, $user->name)
                        ->subject('NumNam - Password Reset Code');
                }
            );
        } catch (\Exception $e) {
            // Log but don't expose mail failure to client
            \Log::error('Password reset email failed: ' . $e->getMessage());
        }

        return $this->success(null, 'If that email exists, a reset code has been sent.');
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $record = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        if (! $record) {
            throw ValidationException::withMessages([
                'code' => ['Invalid or expired reset code.'],
            ]);
        }

        // Check if the code has expired (30 minutes)
        if (now()->diffInMinutes($record->created_at) > 30) {
            DB::table('password_resets')->where('email', $request->email)->delete();
            throw ValidationException::withMessages([
                'code' => ['Reset code has expired. Please request a new one.'],
            ]);
        }

        // Verify the code
        if (! Hash::check($request->code, $record->token)) {
            throw ValidationException::withMessages([
                'code' => ['Invalid reset code.'],
            ]);
        }

        // Reset the password
        $user = User::where('email', $request->email)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => ['No account found with that email.'],
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the used reset record
        DB::table('password_resets')->where('email', $request->email)->delete();

        // Auto-login: issue a new JWT
        $token = $this->jwtService->issueToken($user);

        return $this->success([
            'token_type' => 'Bearer',
            'access_token' => $token,
            'expires_in_minutes' => (int) config('jwt.ttl_minutes', 60),
            'user' => $this->userProfile($user),
        ], 'Password reset successful.');
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

    private function userProfile(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'date_of_birth' => $user->date_of_birth?->toDateString(),
            'gender' => $user->gender,
            'avatar' => $user->avatar ? Storage::disk('public')->url($user->avatar) : null,
            'address_line1' => $user->address_line1,
            'address_line2' => $user->address_line2,
            'city' => $user->city,
            'state' => $user->state,
            'postal_code' => $user->postal_code,
            'country' => $user->country,
            'referral_code' => $user->referral_code,
        ];
    }
}
