<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Inertia\Inertia;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::latest()->get();

        return Inertia::render('Kepsek/Pegawai/Index', [
            'pegawai' => $pegawai
        ]);
    }
}

