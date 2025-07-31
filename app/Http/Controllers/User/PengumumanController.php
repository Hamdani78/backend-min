<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PengumumanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pendaftar = $user->pendaftar;

        if (!$pendaftar) {
            return redirect()->route('pendaftaran.create')->withErrors([
                'error' => 'Anda belum mengisi formulir pendaftaran.'
            ]);
        }

        if (is_null($pendaftar->status_lulus)) {
            return redirect()->route('user.dashboard')->with('error', 'Hasil seleksi belum diumumkan.');
        }

        return Inertia::render('User/Pengumuman/Pengumuman', [
            'pendaftar' => $pendaftar,
            'statusPengumuman' => $pendaftar->status_lulus,
            'nilaiSpk' => $pendaftar->nilai_spk,
        ]);
    }
}
