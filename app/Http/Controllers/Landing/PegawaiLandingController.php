<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;

class PegawaiLandingController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::latest()->get();
        return response()->json($pegawai);
    }
}