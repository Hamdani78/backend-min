<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePendaftaranExists
{
    public function handle($request, Closure $next)
    {
        if (!$request->user()->pendaftar) {
            return redirect()->route('pendaftaran.create')->withErrors(['error' => 'Isi formulir pendaftaran terlebih dahulu.']);
        }

        return $next($request);
    }
}
