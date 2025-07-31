<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::latest()->get();
        return Inertia::render('Admin/Pegawai/Index', ['pegawai' => $pegawai]);
    }

    public function create()
    {
        return Inertia::render('Admin/Pegawai/Tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'nip' => 'required|numeric',
            'email' => 'required|email',
            'status' => 'required|string',
            'foto' => 'nullable|image|max:5120'
        ]);

        $foto = $request->file('foto');
        $fotoName = $foto ? $foto->storeAs('public/pegawai', $foto->hashName()) : null;

        Pegawai::create([
            'nama' => $request->nama,
            'nip' => $request->nip,
            'email' => $request->email,
            'status' => $request->status,
            'foto' => $foto ? $foto->hashName() : null,
        ]);

        return redirect()->route('pegawai.index')->with('success', 'Data Berhasil Disimpan');
    }

    public function edit(Pegawai $pegawai)
    {
        return Inertia::render('Admin/Pegawai/Update', ['pegawai' => $pegawai]);
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $request->validate([
            'nama' => 'required|string',
            'nip' => 'required|numeric',
            'email' => 'required|email',
            'status' => 'required|string',
            'foto' => 'nullable|image|max:5120'
        ]);

        $data = $request->only(['nama', 'nip', 'email', 'status']);

        if ($request->hasFile('foto')) {
            Storage::disk('public')->delete('pegawai/' . $pegawai->foto);
            $foto = $request->file('foto');
            $foto->storeAs('public/pegawai', $foto->hashName());
            $data['foto'] = $foto->hashName();
        } else {
            $data['foto'] = $pegawai->foto;
        }

        $pegawai->update($data);

        return redirect()->route('pegawai.index')->with('success', 'Data Berhasil Diubah');
    }

    public function destroy(Pegawai $pegawai)
    {
        Storage::disk('public')->delete('pegawai/' . $pegawai->foto);
        $pegawai->delete();
        return redirect()->route('pegawai.index')->with('success', 'Data Berhasil Dihapus');
    }
}
