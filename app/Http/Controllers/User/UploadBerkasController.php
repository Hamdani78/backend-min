<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BerkasPendaftaran;
use App\Models\Pendaftar;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UploadBerkasController extends Controller
{
    /**
     * Boleh edit hanya saat status formulir/berkas
     */
    private function canEdit(?Pendaftar $pendaftar): bool
    {
        if (!$pendaftar) return false;
        return in_array($pendaftar->status_pendaftaran, ['formulir', 'berkas'], true);
    }

    private function ensureEditableOrAbort(?Pendaftar $pendaftar)
    {
        if (!$this->canEdit($pendaftar)) {
            abort(403, 'Berkas sudah diverifikasi / terkunci dan tidak bisa diubah.');
        }
    }

    public function create()
    {
        $pendaftar = auth()->user()->pendaftar;
        if (!$pendaftar) {
            return redirect()->route('user.dashboard')
                ->withErrors(['error' => 'Lengkapi data diri terlebih dahulu.']);
        }

        // Jika sudah punya berkas, arahkan ke detail
        if ($pendaftar->berkas) {
            return redirect()->route('user.berkas.show');
        }

        // Jika status sudah terkunci, larang akses form create
        if (!$this->canEdit($pendaftar)) {
            return redirect()->route('user.berkas.show')
                ->withErrors(['error' => 'Berkas sudah diverifikasi dan terkunci.']);
        }

        return Inertia::render('User/UploadBerkas/UploadBerkas', [
            'pendaftar' => $pendaftar,
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
                'kip'            => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            $user = auth()->user();
            $pendaftar = $user->pendaftar;

            // Anti-tamper: pendaftar_id harus milik user login
            if (!$pendaftar || (int)$request->pendaftar_id !== (int)$pendaftar->id) {
                abort(403, 'Tidak diizinkan.');
            }

            // Cegah upload jika status sudah terkunci
            $this->ensureEditableOrAbort($pendaftar);

            if ($pendaftar->berkas || BerkasPendaftaran::where('pendaftar_id', $pendaftar->id)->exists()) {
                return redirect()->route('user.berkas.show')
                    ->withErrors(['error' => 'Berkas sudah pernah diunggah.']);
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

            BerkasPendaftaran::create($data);

            // Status ke 'berkas'
            $pendaftar->update(['status_pendaftaran' => 'berkas']);

            return redirect()->route('user.dashboard')->with('success', 'Berkas berhasil diunggah.');
        } catch (\Throwable $e) {
            Log::error('Upload berkas gagal: '.$e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat mengunggah berkas.']);
        }
    }

    public function show()
    {
        $pendaftar = auth()->user()->pendaftar;
        $berkas = $pendaftar?->berkas;

        return Inertia::render('User/UploadBerkas/DetailBerkas', [
            'berkas'             => $berkas,
            'statusPendaftaran'  => $pendaftar?->status_pendaftaran,
            // kirim boolean siap pakai untuk menyembunyikan tombol edit
            'canEditBerkas'      => $this->canEdit($pendaftar),
        ]);
    }

    public function edit()
    {
        $pendaftar = auth()->user()->pendaftar;
        if (!$pendaftar) abort(404);

        // Kunci di sini
        $this->ensureEditableOrAbort($pendaftar);

        $berkas = $pendaftar->berkas;

        return Inertia::render('User/UploadBerkas/Edit', [
            'pendaftar'        => $pendaftar,
            'berkas'           => $berkas,
            'statusPendaftaran'=> $pendaftar->status_pendaftaran,
        ]);
    }

    public function update(Request $request)
    {
        try {
            $pendaftar = auth()->user()->pendaftar;
            if (!$pendaftar) abort(404);

            // Kunci di sini
            $this->ensureEditableOrAbort($pendaftar);

            $berkas = $pendaftar->berkas;
            if (!$berkas) {
                return redirect()->route('user.berkas.create')
                    ->withErrors(['error' => 'Berkas belum tersedia.']);
            }

            $request->validate([
                'ijazah_tk'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'akte_kelahiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'kartu_keluarga' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'kip'            => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            $data = [];

            if ($request->hasFile('ijazah_tk')) {
                if ($berkas->ijazah_tk && Storage::disk('public')->exists($berkas->ijazah_tk)) {
                    Storage::disk('public')->delete($berkas->ijazah_tk);
                }
                $data['ijazah_tk'] = $request->file('ijazah_tk')->store('berkas/ijazah', 'public');
            }
            if ($request->hasFile('akte_kelahiran')) {
                if ($berkas->akte_kelahiran && Storage::disk('public')->exists($berkas->akte_kelahiran)) {
                    Storage::disk('public')->delete($berkas->akte_kelahiran);
                }
                $data['akte_kelahiran'] = $request->file('akte_kelahiran')->store('berkas/akte', 'public');
            }
            if ($request->hasFile('kartu_keluarga')) {
                if ($berkas->kartu_keluarga && Storage::disk('public')->exists($berkas->kartu_keluarga)) {
                    Storage::disk('public')->delete($berkas->kartu_keluarga);
                }
                $data['kartu_keluarga'] = $request->file('kartu_keluarga')->store('berkas/kk', 'public');
            }
            if ($request->hasFile('kip')) {
                if ($berkas->kip && Storage::disk('public')->exists($berkas->kip)) {
                    Storage::disk('public')->delete($berkas->kip);
                }
                $data['kip'] = $request->file('kip')->store('berkas/kip', 'public');
            }

            if (empty($data)) {
                return back()->withErrors(['error' => 'Tidak ada perubahan berkas.']);
            }

            $berkas->update($data);

            return redirect()->route('user.berkas.show')->with('success', 'Berkas berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error('Update berkas gagal: '.$e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui berkas.']);
        }
    }
}
