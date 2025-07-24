<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FasilitasController extends Controller
{
    public function index()
    {
        $fasilitas = Fasilitas::latest()->paginate(10);
        return Inertia::render('Admin/Fasilitas/Index', [
            'fasilitas' => $fasilitas
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Fasilitas/Tambah');
    }

    public function edit($id)
    {
        $fasilitas = Fasilitas::findOrFail($id);
        return Inertia::render('Admin/Fasilitas/Update', [
            'fasilitas' => $fasilitas
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        Fasilitas::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('fasilitas.index')->with('success', 'Data Berhasil Disimpan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $fasilitas = Fasilitas::findOrFail($id);

        $fasilitas->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('fasilitas.index')->with('success', 'Data Berhasil Diubah!');
    }

    public function destroy($id)
    {
        $fasilitas = Fasilitas::findOrFail($id);
        $fasilitas->delete();

        return redirect()->route('fasilitas.index')->with('success', 'Data Berhasil Dihapus!');
    }
}
