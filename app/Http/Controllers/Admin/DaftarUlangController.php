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
}
