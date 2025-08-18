<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // ===== Kepsek =====
        if ($user->role === 'kepsek') {
            $notifications = $user->notifications()
                ->select('id', 'data', 'created_at', 'read_at')
                ->latest()
                ->limit(20)
                ->get();

            return Inertia::render('Kepsek/Dashboard', [
                'auth'          => ['user' => $user],
                'notifications' => $notifications,
                'flash'         => session()->only(['success','status','error']),
            ]);
        }

        // ===== Pendaftar =====
        $pendaftar = $user->pendaftar?->load(['berkas','daftarUlang']);
        $notifications = $user->notifications()
            ->select('id', 'data', 'created_at', 'read_at')
            ->latest()
            ->limit(20)
            ->get();

        return Inertia::render('User/PendaftarDashboard', [
            'auth'               => ['user' => $user],
            'pendaftar'          => $pendaftar,
            'berkas'             => $pendaftar?->berkas,
            'statusPendaftaran'  => $pendaftar?->status_pendaftaran,
            'statusLulus'        => $pendaftar?->status_lulus,
            'nilaiSpk'           => $pendaftar?->nilai_spk,
            'notifications'      => $notifications,
            'flash'              => session()->only(['success','status','error']),
        ]);
    }
}
