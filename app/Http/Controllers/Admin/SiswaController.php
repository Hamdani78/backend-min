<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SiswaController extends Controller
{
    public function index()
    {
        $siswa = Siswa::with('pegawai')->latest()->paginate(10);

        return Inertia::render('Admin/Siswa/Index', [
            'siswa' => $siswa
        ]);
    }

    public function create()
    {
        $pegawai = Pegawai::select('id', 'nama')->get();

        return Inertia::render('Admin/Siswa/Tambah', [
            'pegawai' => $pegawai
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas' => 'required',
            'jml_siswa' => 'required|numeric',
            'pegawai' => 'required|exists:pegawai,id',
        ]);

        $siswa = Siswa::create([
            'kelas' => $request->kelas,
            'jml_siswa' => $request->jml_siswa,
            'pegawai_id' => $request->pegawai,
        ]);

        return redirect()->route('siswa.index')->with('success', 'Data Berhasil Disimpan');
    }

    public function edit($id)
    {
        $siswa = Siswa::with('pegawai')->findOrFail($id);
        $pegawai = Pegawai::select('id', 'nama')->get();

        return Inertia::render('Admin/Siswa/Update', [
            'siswa' => $siswa,
            'pegawai' => $pegawai,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kelas' => 'required',
            'jml_siswa' => 'required|numeric',
            'pegawai' => 'required|exists:pegawai,id',
        ]);

        $siswa = Siswa::findOrFail($id);
        $siswa->update([
            'kelas' => $request->kelas,
            'jml_siswa' => $request->jml_siswa,
            'pegawai_id' => $request->pegawai,
        ]);

        return redirect()->route('siswa.index')->with('success', 'Data Berhasil Diubah');
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', 'Data Berhasil Dihapus');
    }
}
