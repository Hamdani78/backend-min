<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use Inertia\Inertia;

class PendaftarController extends Controller
{
    public function index()
    {
        $data = Pendaftar::with('user')
            ->orderBy('nama')
            ->get(['id', 'nama', 'status_lulus', 'nilai_spk']);

        return Inertia::render('Kepsek/Pendaftar/Index', [
            'pendaftar' => $data
        ]);
    }
}