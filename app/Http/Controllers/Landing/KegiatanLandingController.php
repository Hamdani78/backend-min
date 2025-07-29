<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;

class KegiatanLandingController extends Controller
{
    public function index()
    {
        $kegiatan = \App\Models\Kegiatan::with('images')->latest()->get();
        return response()->json($kegiatan);
    }
}
