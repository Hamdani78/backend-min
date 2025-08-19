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
            'user_id' => 'nullable|exists:users,id',
            'nama' => 'nullable|string|max:255',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'no_kk' => 'nullable|string|max:20',
            'nik' => 'nullable|string|max:20',
            'anak_ke' => 'nullable|numeric',
            'jumlah_saudara' => 'nullable|numeric',
            'jenis_kelamin' => 'nullable|in:L,P',
            'agama' => 'nullable|string|max:50',
            'berat_badan' => 'nullable|numeric',
            'tinggi_badan' => 'nullable|numeric',
            'cita_cita' => 'nullable|string|max:100',
            'hobi' => 'nullable|string|max:100',
            'bahasa' => 'nullable|string|max:100',
            'keadaan_jasmani' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'provinsi' => 'nullable|string',
            'kabupaten' => 'nullable|string',
            'kecamatan' => 'nullable|string',
            'kelurahan' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'tinggal_dengan' => 'nullable|string',
            'pembiaya' => 'nullable|string',
            'jarak_ke_madrasah' => 'nullable|string',
            'kebutuhan_khusus' => 'nullable|string',
            'kebutuhan_disabilitas' => 'nullable|string',
            'pra_sekolah' => 'nullable|string',
            'nama_pra_sekolah' => 'nullable|string|max:100',
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
        $pendaftar = Pendaftar::with(['berkas', 'orangTuas', 'wali'])->findOrFail($id);

        if ($pendaftar->foto && Storage::disk('public')->exists($pendaftar->foto)) {
            Storage::disk('public')->delete($pendaftar->foto);
        }

        $pendaftar->berkas()?->delete();     
        $pendaftar->orangTuas()->delete();
        $pendaftar->wali()?->delete();

        $pendaftar->delete();

        return redirect()->route('pendaftar.index')->with('status', 'Pendaftar berhasil dihapus.');
    }
}