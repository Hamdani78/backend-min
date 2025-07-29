<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DaftarUlang;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;


class DaftarUlangController extends Controller
{
    public function create()
    {
        return inertia('User/DaftarUlang/DaftarUlang');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'pekerjaan' => 'required|string',
            'agama' => 'required|string',
            'anak' => 'required|string',
            'hubungan' => 'required|string',
            'alamat' => 'required|string',
        ]);

        $pendaftar = auth()->user()->pendaftar;

        if (!$pendaftar) {
            return back()->withErrors(['error' => 'Data pendaftar tidak ditemukan.']);
        }

        $data = $request->only('nama', 'pekerjaan', 'agama', 'anak', 'hubungan', 'alamat');

        $pdf = PDF::loadView('pdf.surat_pernyataan', ['data' => $data]);

        $pdfPath = "daftar_ulang/surat_{$pendaftar->id}.pdf";
        Storage::put("public/{$pdfPath}", $pdf->output());

        $model = DaftarUlang::updateOrCreate(
            ['pendaftar_id' => $pendaftar->id],
            ['file_surat' => $pdfPath, 'status' => 'dikirim']
        );

        return redirect()->route('user.dashboard')->with('success', 'Surat berhasil dikirim.');
    }
}
