<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BerkasPendaftaran;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UploadBerkasController extends Controller
{
    public function create()
    {
        $pendaftar = auth()->user()->pendaftar;

        return Inertia::render('User/UploadBerkas/UploadBerkas', [
            'pendaftar' => $pendaftar,
        ]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'pendaftar_id' => 'required|exists:pendaftars,id',
                'ijazah_tk' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'akte_kelahiran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'kartu_keluarga' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'kip' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            if (BerkasPendaftaran::where('pendaftar_id', $request->pendaftar_id)->exists()) {
                return back()->withErrors(['error' => 'Berkas sudah pernah diunggah.']);
            }

            $data = [
                'pendaftar_id' => $request->pendaftar_id,
                'ijazah_tk' => $request->file('ijazah_tk')->store('berkas/ijazah', 'public'),
                'akte_kelahiran' => $request->file('akte_kelahiran')->store('berkas/akte', 'public'),
                'kartu_keluarga' => $request->file('kartu_keluarga')->store('berkas/kk', 'public'),
            ];

            if ($request->hasFile('kip')) {
                $data['kip'] = $request->file('kip')->store('berkas/kip', 'public');
            }

            BerkasPendaftaran::updateOrCreate(
                ['pendaftar_id' => $request->pendaftar_id],
                $data
            );
            $pendaftar = \App\Models\Pendaftar::find($request->pendaftar_id);
            $pendaftar->update(['status_pendaftaran' => 'berkas']);

            return redirect()->route('user.dashboard')->with('success', 'Berkas berhasil diunggah.');
        } catch (\Exception $e) {
            Log::error('Upload berkas gagal: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat mengunggah berkas.']);
        }
    }
}
