<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePpdbOpen
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Setting::isPpdbOpen()) {
            return $next($request);
        }

        // Ditutup: balas JSON utk API/XHR, redirect utk Inertia/HTML
        if ($request->expectsJson()) {
            return response()->json(['message' => 'PPDB ditutup'], 423);
        }

        return redirect()->route('ppdb.closed');
    }
}
