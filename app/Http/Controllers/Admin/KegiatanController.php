<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KegiatanController extends Controller
{
    /**
     * Tampilkan daftar kegiatan.
     */
    public function index()
    {
        $kegiatan = Kegiatan::latest()->paginate(10);
        return Inertia::render('Admin/Kegiatan/Index', [
            'kegiatan' => $kegiatan
        ]);
    }

    /**
     * Form tambah kegiatan.
     */
    public function create()
    {
        return Inertia::render('Admin/Kegiatan/Tambah');
    }

    /**
     * Simpan kegiatan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kegiatan = Kegiatan::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('kegiatan.index')->with(
            $kegiatan
                ? ['status' => 'Data Berhasil Disimpan']
                : ['error' => 'Data Gagal Disimpan']
        );
    }

    /**
     * Tampilkan detail kegiatan (opsional).
     */
    public function show($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        return Inertia::render('Admin/Kegiatan/Show', compact('kegiatan'));
    }

    /**
     * Form edit kegiatan.
     */
    public function edit($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        return Inertia::render('Admin/Kegiatan/Update', [
            'kegiatan' => $kegiatan,
        ]);
    }

    /**
     * Update kegiatan.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kegiatan = Kegiatan::findOrFail($id);
        $updated = $kegiatan->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('kegiatan.index')->with(
            $updated
                ? ['status' => 'Data Berhasil Diubah!']
                : ['error' => 'Data Gagal Diubah!']
        );
    }

    /**
     * Hapus kegiatan.
     */
    public function destroy($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $deleted = $kegiatan->delete();

        return redirect()->route('kegiatan.index')->with(
            $deleted
                ? ['status' => 'Data Berhasil Dihapus!']
                : ['error' => 'Data Gagal Dihapus!']
        );
    }
}
