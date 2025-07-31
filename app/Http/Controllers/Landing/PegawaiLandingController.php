<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;

class PegawaiLandingController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::orderBy('created_at', 'asc')->get();
        return response()->json($pegawai);
    }
}
