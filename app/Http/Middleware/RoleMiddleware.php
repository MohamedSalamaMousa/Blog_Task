<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$role): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($user->role !== $role) {
            return response()->json(['error' => 'Forbidden: insufficient permission'], 403);
        }

        return $next($request);
    }
}