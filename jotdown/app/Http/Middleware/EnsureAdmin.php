<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user === null) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if (! isset($user->role) || $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden. Admin role required.'], 403);
        }

        return $next($request);
    }
}
