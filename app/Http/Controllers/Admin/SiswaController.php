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
            'laki_laki' => 'required|numeric',
            'perempuan' => 'required|numeric',
            'pegawai_id' => 'required|exists:pegawais,id',
        ]);

        Siswa::create([
            'kelas' => $request->kelas,
            'jml_siswa' => $request->jml_siswa,
            'laki_laki' => $request->laki_laki,
            'perempuan' => $request->perempuan,
            'pegawai_id' => $request->pegawai_id,
        ]);

        return redirect()->route('siswa.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        $pegawai = Pegawai::select('id', 'nama')->get();

        return Inertia::render('Admin/Siswa/Update', [
            'siswa' => $siswa,
            'pegawai' => $pegawai
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kelas' => 'required',
            'jml_siswa' => 'required|numeric',
            'laki_laki' => 'required|numeric',
            'perempuan' => 'required|numeric',
            'pegawai_id' => 'required|exists:pegawais,id',
        ]);

        $siswa = Siswa::findOrFail($id);
        $siswa->update([
            'kelas' => $request->kelas,
            'jml_siswa' => $request->jml_siswa,
            'laki_laki' => $request->laki_laki,
            'perempuan' => $request->perempuan,
            'pegawai_id' => $request->pegawai_id,
        ]);

        return redirect()->route('siswa.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', 'Data Berhasil Dihapus');
    }
}
