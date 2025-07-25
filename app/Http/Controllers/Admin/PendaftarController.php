<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        return inertia('Admin/Pendaftar/Tambah');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::transaction(function () use ($request, &$validated) {
            // Simpan foto jika ada
            if ($request->hasFile('foto')) {
                $validated['foto'] = $request->file('foto')->store('pendaftar', 'public');
            }

            // Simpan data pendaftar
            $pendaftar = Pendaftar::create($validated);

            // Simpan data orang tua
            foreach ($request->input('orang_tuas', []) as $orangTuaData) {
                $pendaftar->orangTuas()->create($orangTuaData);
            }

            // Simpan data wali jika ada
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
            'nama' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::transaction(function () use ($pendaftar, &$validated, $request) {
            // Upload foto baru jika ada
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
        $pendaftar = Pendaftar::findOrFail($id);

        // Hapus foto jika ada
        if ($pendaftar->foto && Storage::disk('public')->exists($pendaftar->foto)) {
            Storage::disk('public')->delete($pendaftar->foto);
        }

        // Hapus relasi
        $pendaftar->orangTuas()->delete();
        $pendaftar->wali()->delete();

        // Hapus data utama
        $pendaftar->delete();

        return redirect()->route('pendaftar.index')->with('status', 'Pendaftar berhasil dihapus.');
    }
}
