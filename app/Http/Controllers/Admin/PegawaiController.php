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
        $pegawai = Pegawai::latest()->get(); // tampilkan semua (aktif & non-aktif) di admin
        return Inertia::render('Admin/Pegawai/Index', ['pegawai' => $pegawai]);
    }

    public function create()
    {
        return Inertia::render('Admin/Pegawai/Tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'        => 'required|string',
            'nip'         => 'required|numeric',
            'email'       => 'required|email',
            'bidang_ajar' => 'required|string',
            'is_active'   => 'required|boolean',
            'foto'        => 'nullable|image|max:5120'
        ]);

        $foto = $request->file('foto');
        $fotoName = $foto ? $foto->storeAs('public/pegawai', $foto->hashName()) : null;

        Pegawai::create([
            'nama'        => $request->nama,
            'nip'         => $request->nip,
            'email'       => $request->email,
            'bidang_ajar' => $request->bidang_ajar,
            'is_active'   => $request->boolean('is_active'),
            'foto'        => $foto ? $foto->hashName() : null,
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
            'nama'        => 'required|string',
            'nip'         => 'required|numeric',
            'email'       => 'required|email',
            'bidang_ajar' => 'required|string',
            'is_active'   => 'required|boolean',
            'foto'        => 'nullable|image|max:5120'
        ]);

        $data = $request->only(['nama', 'nip', 'email', 'bidang_ajar', 'is_active']);
        $data['is_active'] = $request->boolean('is_active');

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
}
