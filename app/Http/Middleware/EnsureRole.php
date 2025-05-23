<?php

// app/Http/Middleware/EnsureRole.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if ($request->route()->uri() === 'api/admin/user') {
            return $next($request);
        }

        if ($request->user()->getType() !== $role) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}

