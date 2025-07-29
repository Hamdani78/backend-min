<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pendaftar;
use App\Models\OrangTua;
use App\Models\Wali;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PendaftarController extends Controller
{
    public function index()
    {
        $pendaftars = Pendaftar::with('orangTuas', 'wali')->latest()->get();

        return inertia('Admin/Pendaftar/Index', [
            'pendaftars' => $pendaftars
        ]);
    }

    public function create()
    {
        $users = User::where('role', 'pendaftar')->get();

        return inertia('Admin/Pendaftar/Tambah', [
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'no_kk' => 'required|string|max:20',
            'nik' => 'required|string|max:20',
            'anak_ke' => 'required|numeric',
            'jumlah_saudara' => 'required|numeric',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:50',
            'berat_badan' => 'required|numeric',
            'tinggi_badan' => 'required|numeric',
            'cita_cita' => 'required|string|max:100',
            'hobi' => 'required|string|max:100',
            'bahasa' => 'required|string|max:100',
            'keadaan_jasmani' => 'required|string|max:100',
            'alamat' => 'required|string',
            'provinsi' => 'required|string',
            'kabupaten' => 'required|string',
            'kecamatan' => 'required|string',
            'kelurahan' => 'required|string',
            'no_hp' => 'required|string|max:20',
            'tinggal_dengan' => 'required|string',
            'pembiaya' => 'required|string',
            'jarak_ke_madrasah' => 'required|string',
            'kebutuhan_khusus' => 'nullable|string',
            'kebutuhan_disabilitas' => 'nullable|string',
            'pra_sekolah' => 'required|string',
            'nama_pra_sekolah' => 'required|string|max:100',
            'kip_nama' => 'nullable|string',
            'kip_nomor' => 'nullable|string',
            'foto' => $request->hasFile('foto') ? 'image|mimes:jpg,jpeg,png|max:5120' : 'nullable',
            'imunisasi' => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $validated) {
            if ($request->hasFile('foto')) {
                $validated['foto'] = $request->file('foto')->store('pendaftar', 'public');
            }

            $validated['user_id'] = $validated['user_id'] ?? auth()->id();

            if (!$validated['user_id']) {
                throw new \Exception('User ID harus diisi atau login sebagai user.');
            }

            $pendaftar = Pendaftar::create($validated);

            foreach ($request->input('orang_tuas', []) as $orangTuaData) {
                $pendaftar->orangTuas()->create($orangTuaData);
            }

            if ($request->has('wali')) {
                $pendaftar->wali()->create($request->input('wali'));
            }
        });

        return redirect()->route('pendaftar.index')->with('status', 'Pendaftar berhasil ditambahkan.');
    }

    public function show($id)
    {
        $pendaftar = Pendaftar::with('orangTuas', 'wali')->findOrFail($id);

        return inertia('Admin/Pendaftar/Detail', [
            'pendaftar' => $pendaftar
        ]);
    }

    public function edit($id)
    {
        $pendaftar = Pendaftar::with('orangTuas', 'wali')->findOrFail($id);

        return inertia('Admin/Pendaftar/Update', [
            'pendaftar' => $pendaftar
        ]);
    }

    public function update(Request $request, $id)
    {
        $pendaftar = Pendaftar::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'no_kk' => 'required|string|max:20',
            'nik' => 'required|string|max:20',
            'anak_ke' => 'required|numeric',
            'jumlah_saudara' => 'required|numeric',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:50',
            'berat_badan' => 'required|numeric',
            'tinggi_badan' => 'required|numeric',
            'cita_cita' => 'required|string|max:100',
            'hobi' => 'required|string|max:100',
            'bahasa' => 'required|string|max:100',
            'keadaan_jasmani' => 'required|string|max:100',
            'alamat' => 'required|string',
            'provinsi' => 'required|string',
            'kabupaten' => 'required|string',
            'kecamatan' => 'required|string',
            'kelurahan' => 'required|string',
            'no_hp' => 'required|string|max:20',
            'tinggal_dengan' => 'required|string',
            'pembiaya' => 'required|string',
            'jarak_ke_madrasah' => 'required|string',
            'kebutuhan_khusus' => 'nullable|string',
            'kebutuhan_disabilitas' => 'nullable|string',
            'pra_sekolah' => 'required|string',
            'nama_pra_sekolah' => 'required|string|max:100',
            'kip_nama' => 'nullable|string',
            'kip_nomor' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'imunisasi' => 'nullable|array',
        ]);

        DB::transaction(function () use ($pendaftar, $validated, $request) {
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($pendaftar->foto && Storage::disk('public')->exists($pendaftar->foto)) {
                    Storage::disk('public')->delete($pendaftar->foto);
                }

                // Simpan foto baru
                $validated['foto'] = $request->file('foto')->store('pendaftar', 'public');
            }

            // Update data pendaftar
            $pendaftar->update($validated);

            // Update Orang Tua
            $pendaftar->orangTuas()->delete();
            foreach ($request->input('orang_tuas', []) as $orangTuaData) {
                $pendaftar->orangTuas()->create($orangTuaData);
            }

            // Update atau hapus Wali
            if ($request->filled('wali')) {
                $pendaftar->wali()->updateOrCreate([], $request->input('wali'));
            } else {
                $pendaftar->wali()->delete();
            }
        });

        return redirect()->route('pendaftar.index')->with('status', 'Pendaftar berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pendaftar = Pendaftar::with('berkas')->findOrFail($id);

        // Hapus foto jika ada
        if ($pendaftar->foto && Storage::disk('public')->exists($pendaftar->foto)) {
            Storage::disk('public')->delete($pendaftar->foto);
        }

        // Hapus file dan data berkas jika ada
        if ($pendaftar->berkas) {
            $berkas = $pendaftar->berkas;

            if ($berkas->ijazah_tk && Storage::disk('public')->exists($berkas->ijazah_tk)) {
                Storage::disk('public')->delete($berkas->ijazah_tk);
            }
            if ($berkas->akte_kelahiran && Storage::disk('public')->exists($berkas->akte_kelahiran)) {
                Storage::disk('public')->delete($berkas->akte_kelahiran);
            }
            if ($berkas->kartu_keluarga && Storage::disk('public')->exists($berkas->kartu_keluarga)) {
                Storage::disk('public')->delete($berkas->kartu_keluarga);
            }
            if ($berkas->kip && Storage::disk('public')->exists($berkas->kip)) {
                Storage::disk('public')->delete($berkas->kip);
            }

            $berkas->delete();
        }

        // Hapus relasi orang tua dan wali
        $pendaftar->orangTuas()->delete();
        $pendaftar->wali()->delete();

        // Hapus pendaftar
        $pendaftar->delete();

        return redirect()->route('pendaftar.index')->with('status', 'Pendaftar berhasil dihapus.');
    }
}
