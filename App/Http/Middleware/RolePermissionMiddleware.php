<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RolePermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role = null, $permission = null): Response
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        // Check Role
        if ($role) {
            if (!$user->role || $user->role->role !== $role) {
                return response()->json(['message' => 'Forbidden: Insufficient Role'], 403);
            }
        }

        // Check Permission
        if ($permission) {
            $userPermissions = $user->role ? $user->role->permissions->pluck('permission_key')->toArray() : [];

            if (!$user->role || !in_array($permission, $userPermissions)) {
                return response()->json(['message' => 'Forbidden: Insufficient Permission'], 403);
            }
        }

        return $next($request);
    }
}
