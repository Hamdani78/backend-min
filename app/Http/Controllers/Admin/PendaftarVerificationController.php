<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use Illuminate\Http\Request;

class PendaftarVerificationController extends Controller
{
    public function verify(Pendaftar $pendaftar)
    {
        if ($pendaftar->is_verified) {
            return back()->with('status', 'Data sudah dalam status diverifikasi.');
        }

        $pendaftar->forceFill([
            'is_verified'       => true,
            'verified_at'       => now(),
            'verified_by'       => auth('admin')->id(), 
            'verification_note' => null,                
        ])->save();

        return back()->with('status', 'Data pendaftar telah diverifikasi & formulir user terkunci.');
    }

    public function unverify(Pendaftar $pendaftar)
    {
        if (!$pendaftar->is_verified) {
            return back()->with('status', 'Verifikasi sudah dibatalkan sebelumnya.');
        }

        $pendaftar->forceFill([
            'is_verified' => false,
            'verified_at' => null,
            'verified_by' => null,
        ])->save();

        return back()->with('status', 'Verifikasi dibatalkan. Formulir user dapat diedit kembali.');
    }

    public function requestFix(Request $request, Pendaftar $pendaftar)
    {
        $data = $request->validate([
            'verification_note' => 'required|string|max:5000'
        ]);

        // set ke pending + simpan catatan
        $pendaftar->forceFill([
            'is_verified'       => false,
            'verified_at'       => null,
            'verified_by'       => null,
            'verification_note' => $data['verification_note'],
        ])->save();

        return back()->with('status', 'Catatan perbaikan dikirim. User dapat memperbaiki datanya.');
    }
}
