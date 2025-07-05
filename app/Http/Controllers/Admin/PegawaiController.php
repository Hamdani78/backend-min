<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pegawai = Pegawai::latest()->paginate(10);
        return view('admin.table.pegawai.index', compact('pegawai'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.table.pegawai.tambah');
    }
    /**
     * Store a newly created resource in storage.
     */
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
        if ($pegawai) {
            return redirect()->route('pegawai.index')->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return redirect()->route('pegawai.index')->with(['error' => 'Data Gagal Disimpan']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $pegawai = Pegawai::findOrFail($id);
        return view('admin.table.pegawai.show', compact('pegawai'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pegawai = Pegawai::find($id);
        return view('admin.table.pegawai.update', compact('pegawai'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pegawai $pegawai)
    {
        $foto = $request->file('foto');
        if ($foto != null) {
            Storage::disk('local')->delete('public/pegawai/' . $pegawai->foto);
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
        if ($pegawai) {
            return redirect()->route('pegawai.index')->with(['success' => 'Data Berhasil Diubah!']);
        } else {
            return redirect()->route('pegawai.index')->with(['error' => 'Data Gagal Diubah!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->delete();
        if ($pegawai) {
            return redirect()->route('pegawai.index')->with(['success' => 'Data Berhasil Dihapus!']);
        } else {
            return redirect()->route('pegawai.index')->with(['error' => 'Data Gagal Dihapus!']);
        }
    }
}
