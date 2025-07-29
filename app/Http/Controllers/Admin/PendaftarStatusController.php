<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use Illuminate\Http\Request;

class PendaftarStatusController extends Controller
{
    public function update(Request $request, Pendaftar $pendaftar)
    {
        $request->validate([
            'status' => 'required|in:berkas,wawancara,verifikasi,selesai',
        ]);

        $pendaftar->status_pendaftaran = $request->status;
        $pendaftar->save();

        return back()->with('success', 'Status pendaftaran berhasil diubah ke: ' . ucfirst($request->status));
    }
}
