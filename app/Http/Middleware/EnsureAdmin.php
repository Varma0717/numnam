<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user || ! $user->is_admin) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin access is required.',
                ], 403);
            }

            return redirect()->route('admin.login')
                ->withErrors(['email' => 'Admin access is required.']);
        }

        return $next($request);
    }
}
