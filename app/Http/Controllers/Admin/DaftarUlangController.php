<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DaftarUlang;
use Illuminate\Http\Request;

class DaftarUlangController extends Controller
{
    public function index()
    {
        $daftarUlang = DaftarUlang::with('pendaftar.user')->latest()->get();

        return inertia('Admin/DaftarUlang/Index', [
            'daftarUlang' => $daftarUlang
        ]);
    }
    public function verifikasi($id)
    {
        $surat = DaftarUlang::findOrFail($id);

        if ($surat->status === 'diverifikasi') {
            return back()->with('error', 'Surat sudah diverifikasi sebelumnya.');
        }

        $surat->update(['status' => 'diverifikasi']);

        return back()->with('success', 'Surat berhasil diverifikasi.');
    }
    public function selesaikan($id)
    {
        $daftarUlang = \App\Models\DaftarUlang::findOrFail($id);

        $pendaftar = $daftarUlang->pendaftar;
        if ($pendaftar) {
            $pendaftar->update(['status_pendaftaran' => 'selesai']);
            return back()->with('success', 'Pendaftar berhasil ditandai sebagai selesai.');
        }

        return back()->with('error', 'Data pendaftar tidak ditemukan.');
    }
}
