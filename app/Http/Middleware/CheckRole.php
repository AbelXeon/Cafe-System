<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = $request->user();

        if (! $user || ! $user->role || $user->role->name !== $role) {
            abort(403, 'You are not allowed to access this page.');
        }

        return $next($request);
    }
}