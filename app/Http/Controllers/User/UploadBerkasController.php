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
        $berkas = $pendaftar->berkas;

        // Cegah akses jika sudah upload
        if ($berkas) {
            return redirect()->route('user.berkas.show');
        }

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

            BerkasPendaftaran::create($data);

            $pendaftar = \App\Models\Pendaftar::find($request->pendaftar_id);
            $pendaftar->update(['status_pendaftaran' => 'berkas']);

            return redirect()->route('user.dashboard')->with('success', 'Berkas berhasil diunggah.');
        } catch (\Exception $e) {
            Log::error('Upload berkas gagal: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat mengunggah berkas.']);
        }
    }

    public function show()
    {
        $pendaftar = auth()->user()->pendaftar;
        $berkas = $pendaftar->berkas;

        return Inertia::render('User/UploadBerkas/DetailBerkas', [
            'berkas' => $berkas,
        ]);
    }

    public function edit()
    {
        $pendaftar = auth()->user()->pendaftar;
        $berkas = $pendaftar->berkasPendaftaran;

        return Inertia::render('User/UploadBerkas/Edit', [
            'pendaftar' => $pendaftar,
            'berkas' => $berkas,
        ]);
    }

    public function update(Request $request)
    {
        try {
            $pendaftar = auth()->user()->pendaftar;
            $berkas = $pendaftar->berkas;

            if (!$berkas) {
                return redirect()->route('user.berkas.create')->withErrors(['error' => 'Berkas belum tersedia.']);
            }

            $request->validate([
                'ijazah_tk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'akte_kelahiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'kartu_keluarga' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'kip' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            $data = [];

            if ($request->hasFile('ijazah_tk')) {
                Storage::disk('public')->delete($berkas->ijazah_tk);
                $data['ijazah_tk'] = $request->file('ijazah_tk')->store('berkas/ijazah', 'public');
            }

            if ($request->hasFile('akte_kelahiran')) {
                Storage::disk('public')->delete($berkas->akte_kelahiran);
                $data['akte_kelahiran'] = $request->file('akte_kelahiran')->store('berkas/akte', 'public');
            }

            if ($request->hasFile('kartu_keluarga')) {
                Storage::disk('public')->delete($berkas->kartu_keluarga);
                $data['kartu_keluarga'] = $request->file('kartu_keluarga')->store('berkas/kk', 'public');
            }

            if ($request->hasFile('kip')) {
                Storage::disk('public')->delete($berkas->kip);
                $data['kip'] = $request->file('kip')->store('berkas/kip', 'public');
            }

            $berkas->update($data);

            return redirect()->route('user.berkas.show')->with('success', 'Berkas berhasil diperbarui.');
            
        } catch (\Exception $e) {
            Log::error('Update berkas gagal: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui berkas.']);
        }
    }
}
