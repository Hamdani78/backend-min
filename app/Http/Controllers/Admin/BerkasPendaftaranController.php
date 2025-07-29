<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BerkasPendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;


class BerkasPendaftaranController extends Controller
{
    public function index()
    {
        $berkas = BerkasPendaftaran::with('pendaftar')->latest()->get();
        return Inertia::render('Admin/Berkas/Index', [
            'berkas' => $berkas
        ]);
    }

    public function create()
    {
        $pendaftars = \App\Models\Pendaftar::with('user')->get(['id', 'nama']);
        return Inertia::render('Admin/Berkas/Tambah', [
            'pendaftars' => $pendaftars
        ]);
    }

    public function store(Request $request)
    {
        try {
            $user = auth()->user();

            $request->validate([
                'pendaftar_id' => 'required|exists:pendaftars,id',
                'ijazah_tk' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'akte_kelahiran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'kartu_keluarga' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'kip' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);

            $pendaftar = \App\Models\Pendaftar::find($request->pendaftar_id);

            if (!$pendaftar) {
                return back()->withErrors(['error' => 'Pendaftar tidak ditemukan.']);
            }

            if (BerkasPendaftaran::where('pendaftar_id', $pendaftar->id)->exists()) {
                return back()->withErrors(['error' => 'Berkas sudah pernah diunggah.']);
            }

            $data = [
                'pendaftar_id' => $pendaftar->id,
                'ijazah_tk' => $request->file('ijazah_tk')->store('berkas/ijazah', 'public'),
                'akte_kelahiran' => $request->file('akte_kelahiran')->store('berkas/akte', 'public'),
                'kartu_keluarga' => $request->file('kartu_keluarga')->store('berkas/kk', 'public'),
            ];

            if ($request->hasFile('kip')) {
                $data['kip'] = $request->file('kip')->store('berkas/kip', 'public');
            }

            $berkas = BerkasPendaftaran::create($data);
            Log::info('Berkas berhasil disimpan!', ['id' => $berkas->id]);

            return redirect()->route('berkas-pendaftaran.index')->with('success', 'Berkas berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan data berkas:', ['msg' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data.']);
        }
    }
    public function edit(BerkasPendaftaran $berkas_pendaftaran)
    {
        $pendaftars = \App\Models\Pendaftar::all(['id', 'nama']);

        return Inertia::render('Admin/Berkas/Update', [
            'berkas' => $berkas_pendaftaran,
            'pendaftars' => $pendaftars,
        ]);
    }

    public function update(Request $request, BerkasPendaftaran $berkas_pendaftaran)
    {
        $request->validate([
            'ijazah_tk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'akte_kelahiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'kartu_keluarga' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'kip' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('ijazah_tk')) {
            Storage::disk('public')->delete($berkas_pendaftaran->ijazah_tk);
            $berkas_pendaftaran->ijazah_tk = $request->file('ijazah_tk')->store('berkas/ijazah', 'public');
        }

        if ($request->hasFile('akte_kelahiran')) {
            Storage::disk('public')->delete($berkas_pendaftaran->akte_kelahiran);
            $berkas_pendaftaran->akte_kelahiran = $request->file('akte_kelahiran')->store('berkas/akte', 'public');
        }

        if ($request->hasFile('kartu_keluarga')) {
            Storage::disk('public')->delete($berkas_pendaftaran->kartu_keluarga);
            $berkas_pendaftaran->kartu_keluarga = $request->file('kartu_keluarga')->store('berkas/kk', 'public');
        }

        if ($request->hasFile('kip')) {
            if ($berkas_pendaftaran->kip) {
                Storage::disk('public')->delete($berkas_pendaftaran->kip);
            }
            $berkas_pendaftaran->kip = $request->file('kip')->store('berkas/kip', 'public');
        }

        $berkas_pendaftaran->save();

        return redirect()->route('berkas-pendaftaran.index')->with('success', 'Berkas berhasil diperbarui.');
    }


    public function destroy(BerkasPendaftaran $berkas_pendaftaran)
    {
        Log::info('Memanggil fungsi destroy', ['id' => $berkas_pendaftaran->id]);

        Storage::disk('public')->delete(array_filter([
            $berkas_pendaftaran->ijazah_tk,
            $berkas_pendaftaran->akte_kelahiran,
            $berkas_pendaftaran->kartu_keluarga,
            $berkas_pendaftaran->kip,
        ]));

        $berkas_pendaftaran->delete();

        return back()->with('success', 'Berkas berhasil dihapus.');
    }
}
