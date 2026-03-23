<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Support\JwtService;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JwtAuthenticate
{
    private JwtService $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (! $token) {
            return response()->json([
                'success' => false,
                'message' => 'Missing bearer token.',
                'errors' => [
                    'token' => ['Authorization header with bearer token is required.'],
                ],
            ], 401);
        }

        $payload = $this->jwtService->parseToken($token);
        if (! $payload || empty($payload['sub'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired token.',
            ], 401);
        }

        $user = User::find($payload['sub']);
        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Token user no longer exists.',
            ], 401);
        }

        Auth::setUser($user);
        $request->setUserResolver(static fn () => $user);

        return $next($request);
    }
}
