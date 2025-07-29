<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;

class FasilitasLandingController extends Controller
{
    public function index()
    {
        $fasilitas = \App\Models\Fasilitas::with('images')->latest()->get();
        return response()->json($fasilitas);
    }
}
