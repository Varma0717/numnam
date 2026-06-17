<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Support\JwtService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthSessionOrJwt
{
    private JwtService $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public function handle(Request $request, Closure $next)
    {
        // First, try session authentication (website tracker)
        // Check the web guard which is used for session auth
        $sessionUser = Auth::guard('web')->user();
        if ($sessionUser) {
            // Set the user in the default auth for compatibility
            Auth::setUser($sessionUser);
            return $next($request);
        }

        // If no session, try JWT authentication (mobile app)
        $token = $request->bearerToken();
        if ($token) {
            $payload = $this->jwtService->parseToken($token);
            if ($payload && !empty($payload['sub'])) {
                $user = User::find($payload['sub']);
                if ($user) {
                    // Set the user in the default auth
                    Auth::setUser($user);
                    return $next($request);
                }
            }
        }

        // No valid authentication found
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated. Please log in or provide a valid JWT token.',
        ], 401);
    }
}
