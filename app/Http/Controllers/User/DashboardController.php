<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'kepsek') {
            return Inertia::render('User/KepsekDashboard', [
                'auth' => ['user' => $user],
            ]);
        }

        $pendaftar = $user->pendaftar;
        $berkas = $pendaftar?->berkas;

        return Inertia::render('User/PendaftarDashboard', [
            'auth' => ['user' => $user],
            'pendaftar' => $pendaftar,
            'berkas' => $berkas,
            'statusPendaftaran' => $pendaftar?->status_pendaftaran,
        ]);
    }
}
