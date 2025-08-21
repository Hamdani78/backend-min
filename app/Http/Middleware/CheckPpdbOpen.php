<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Setting;

class CheckPpdbOpen
{
    public function handle($request, Closure $next)
    {
        if (!Setting::isPpdbOpen()) {
            // Bisa redirect ke halaman info atau kembalikan 403
            return redirect()->route('ppdb.closed');
        }
        return $next($request);
    }
}