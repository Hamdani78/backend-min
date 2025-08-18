<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BerkasPendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class BerkasPendaftaranController extends Controller
{
    public function index()
    {
        // kalau ingin paginate: ->latest()->paginate(15)
        $berkas = BerkasPendaftaran::with('pendaftar')->latest()->get();

        return Inertia::render('Admin/Berkas/Index', [
            'berkas' => $berkas
        ]);
    }

    public function create()
    {
        // with('user') tidak dipakai di form; cukup id & nama saja
        $pendaftars = \App\Models\Pendaftar::orderBy('nama')->get(['id', 'nama']);

        return Inertia::render('Admin/Berkas/Tambah', [
            'pendaftars' => $pendaftars
        ]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'pendaftar_id'   => 'required|exists:pendaftars,id',
                'ijazah_tk'      => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'akte_kelahiran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'kartu_keluarga' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                // samakan KIP 2MB kalau mau konsisten dengan sisi user:
                'kip'            => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            $pendaftar = \App\Models\Pendaftar::find($request->pendaftar_id);
            if (!$pendaftar) {
                return back()->withErrors(['error' => 'Pendaftar tidak ditemukan.']);
            }

            if (BerkasPendaftaran::where('pendaftar_id', $pendaftar->id)->exists()) {
                return back()->withErrors(['error' => 'Berkas sudah pernah diunggah.']);
            }

            $data = [
                'pendaftar_id'   => $pendaftar->id,
                'ijazah_tk'      => $request->file('ijazah_tk')->store('berkas/ijazah', 'public'),
                'akte_kelahiran' => $request->file('akte_kelahiran')->store('berkas/akte', 'public'),
                'kartu_keluarga' => $request->file('kartu_keluarga')->store('berkas/kk', 'public'),
            ];
            if ($request->hasFile('kip')) {
                $data['kip'] = $request->file('kip')->store('berkas/kip', 'public');
            }

            $berkas = BerkasPendaftaran::create($data);

            $pendaftar->update(['status_pendaftaran' => 'berkas']);

            Log::info('Berkas berhasil disimpan!', ['id' => $berkas->id]);

            return redirect()->route('berkas-pendaftaran.index')->with('success', 'Berkas berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan data berkas:', ['msg' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data.']);
        }
    }

    public function edit(BerkasPendaftaran $berkas_pendaftaran)
    {
        $pendaftars = \App\Models\Pendaftar::orderBy('nama')->get(['id', 'nama']);

        return Inertia::render('Admin/Berkas/Update', [
            'berkas'      => $berkas_pendaftaran,
            'pendaftars'  => $pendaftars,
        ]);
    }

    public function update(Request $request, BerkasPendaftaran $berkas_pendaftaran)
    {
        $request->validate([
            'ijazah_tk'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'akte_kelahiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'kartu_keluarga' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            // samakan KIP 2MB jika perlu konsisten:
            'kip'            => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = [];

        if ($request->hasFile('ijazah_tk')) {
            if ($berkas_pendaftaran->ijazah_tk && Storage::disk('public')->exists($berkas_pendaftaran->ijazah_tk)) {
                Storage::disk('public')->delete($berkas_pendaftaran->ijazah_tk);
            }
            $data['ijazah_tk'] = $request->file('ijazah_tk')->store('berkas/ijazah', 'public');
        }

        if ($request->hasFile('akte_kelahiran')) {
            if ($berkas_pendaftaran->akte_kelahiran && Storage::disk('public')->exists($berkas_pendaftaran->akte_kelahiran)) {
                Storage::disk('public')->delete($berkas_pendaftaran->akte_kelahiran);
            }
            $data['akte_kelahiran'] = $request->file('akte_kelahiran')->store('berkas/akte', 'public');
        }

        if ($request->hasFile('kartu_keluarga')) {
            if ($berkas_pendaftaran->kartu_keluarga && Storage::disk('public')->exists($berkas_pendaftaran->kartu_keluarga)) {
                Storage::disk('public')->delete($berkas_pendaftaran->kartu_keluarga);
            }
            $data['kartu_keluarga'] = $request->file('kartu_keluarga')->store('berkas/kk', 'public');
        }

        if ($request->hasFile('kip')) {
            if ($berkas_pendaftaran->kip && Storage::disk('public')->exists($berkas_pendaftaran->kip)) {
                Storage::disk('public')->delete($berkas_pendaftaran->kip);
            }
            $data['kip'] = $request->file('kip')->store('berkas/kip', 'public');
        }

        if (empty($data)) {
            return back()->withErrors(['error' => 'Tidak ada perubahan berkas.']);
        }

        $berkas_pendaftaran->update($data);

        return redirect()->route('berkas-pendaftaran.index')->with('success', 'Berkas berhasil diperbarui.');
    }

    public function destroy(BerkasPendaftaran $berkas_pendaftaran)
    {
        Log::info('Memanggil fungsi destroy', ['id' => $berkas_pendaftaran->id]);

        // array_filter sudah mencegah null, delete aman saat file tidak ada
        Storage::disk('public')->delete(array_filter([
            $berkas_pendaftaran->ijazah_tk,
            $berkas_pendaftaran->akte_kelahiran,
            $berkas_pendaftaran->kartu_keluarga,
            $berkas_pendaftaran->kip,
        ]));

        $berkas_pendaftaran->delete();

        return back()->with('success', 'Berkas berhasil dihapus.');
    }

    public function verify(BerkasPendaftaran $berkas_pendaftaran)
    {
        // kunci sisi user
        if ($berkas_pendaftaran->pendaftar) {
            $berkas_pendaftaran->pendaftar->update([
                'status_pendaftaran' => 'berkas_terverifikasi',
            ]);
        }


        return back()->with('success', 'Berkas diverifikasi. User terkunci dari edit.');
    }

    public function setWawancara(Request $request, BerkasPendaftaran $berkas_pendaftaran)
    {
        $request->validate([
            'jadwal' => 'required|date_format:Y-m-d\TH:i',
            'tempat' => 'nullable|string|max:150',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $p = $berkas_pendaftaran->pendaftar;
        if ($p) {
            // Anggap input selalu WIB:
            $local = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->jadwal, 'Asia/Jakarta');

            // Simpan UTC ke DB:
            $utc = $local->clone()->utc();

            $p->update([
                'status_pendaftaran' => 'wawancara',
                'wawancara_at'       => $utc,              // <-- UTC
                'wawancara_tempat'   => $request->tempat,
                'wawancara_catatan'  => $request->catatan,
            ]);
        }

        return back()->with('success', 'Jadwal wawancara disimpan.');
    }
}
