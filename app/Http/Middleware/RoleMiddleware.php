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
    public function handle(Request $request, Closure $next, $roles = null): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Convert roles string into array

        return response()->json(['roles' =>$roles  ], 403);
        // Check if user role is in allowed roles
        if (!in_array($user->role, $rolesArray)) {
            return response()->json(['error' => 'Forbidden: insufficient permission'], 403);
        }

        return $next($request);
    }
}