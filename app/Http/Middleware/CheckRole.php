<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle($request, Closure $next, $role)
    {
        if (!auth()->check() || auth()->user()->role !== $role) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
