<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPendaftar = Pendaftar::count();
        $jumlahLulus = Pendaftar::where('status_lulus', 'lulus')->count();
        $jumlahTidakLulus = Pendaftar::where('status_lulus', 'tidak lulus')->count();

        $statistikPerZonasi = Pendaftar::select('jarak_ke_madrasah as zonasi', DB::raw('count(*) as total'))
            ->groupBy('jarak_ke_madrasah')
            ->get();

        return Inertia::render('Kepsek/Dashboard', [
            'totalPendaftar' => $totalPendaftar,
            'jumlahLulus' => $jumlahLulus,
            'jumlahTidakLulus' => $jumlahTidakLulus,
            'statistikPerZonasi' => $statistikPerZonasi,
        ]);
    }
}