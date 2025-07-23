<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::all();
        return Inertia::render('Admin/Pegawai/Index', [
            'pegawai' => $pegawai
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Pegawai/Create');
    }

    public function store(Request $request)
    {
        $foto = $request->file('foto');
        $foto->storeAs('public/pegawai', $foto->hashName());

        $pegawai = Pegawai::create([
            'nama' => $request->nama,
            'nip' => $request->nip,
            'email' => $request->email,
            'status' => $request->status,
            'foto' => $foto->hashName(),
        ]);

        return redirect()->route('pegawai.index')->with('success', 'Data Berhasil Disimpan');
    }

    public function show($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        return Inertia::render('Admin/Pegawai/Show', [
            'pegawai' => $pegawai
        ]);
    }

    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        return Inertia::render('Admin/Pegawai/Edit', [
            'pegawai' => $pegawai
        ]);
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        if ($request->hasFile('foto')) {
            Storage::disk('public')->delete('pegawai/' . $pegawai->foto);
            $foto = $request->file('foto');
            $foto->storeAs('public/pegawai', $foto->hashName());
            $fotoName = $foto->hashName();
        } else {
            $fotoName = $pegawai->foto;
        }

        $pegawai->update([
            'nama' => $request->nama,
            'nip' => $request->nip,
            'email' => $request->email,
            'status' => $request->status,
            'foto' => $fotoName,
        ]);

        return redirect()->route('pegawai.index')->with('success', 'Data Berhasil Diubah!');
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        Storage::disk('public')->delete('pegawai/' . $pegawai->foto);
        $pegawai->delete();

        return redirect()->route('pegawai.index')->with('success', 'Data Berhasil Dihapus!');
    }
}
