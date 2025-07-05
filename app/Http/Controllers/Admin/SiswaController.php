<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswa = Siswa::latest()->paginate(10);
        return view('admin.table.siswa.index', compact('siswa'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pegawai = Pegawai::all();
        return view('admin.table.siswa.tambah', compact('pegawai'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas' => 'required',
            'jml_siswa' => 'required|numeric',
            'pegawai' => 'required', // Pastikan pegawai dipilih
        ]);

        $siswa = Siswa::create([
            'kelas' => $request->kelas,
            'jml_siswa' => $request->jml_siswa,
            'pegawai_id' => $request->pegawai, // Gunakan id pegawai dari input
        ]);

        if ($siswa) {
            return redirect()->route('siswa.index')->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return redirect()->route('siswa.index')->with(['error' => 'Data Gagal Disimpan']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $siswa = Siswa::find($id);
        $pegawai = Pegawai::all(); // Ambil semua pegawai untuk ditampilkan di form edit
        return view('admin.table.siswa.update', compact('siswa', 'pegawai'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kelas' => 'required',
            'jml_siswa' => 'required|numeric',
            'pegawai' => 'required', // Pastikan pegawai dipilih
        ]);

        $siswa = Siswa::findOrFail($id);
        $siswa->update([
            'kelas' => $request->kelas,
            'jml_siswa' => $request->jml_siswa,
            'pegawai_id' => $request->pegawai, // Gunakan id pegawai dari input
        ]);

        if ($siswa) {
            return redirect()->route('siswa.index')->with(['success' => 'Data Berhasil Diubah!']);
        } else {
            return redirect()->route('siswa.index')->with(['error' => 'Data Gagal Diubah!']);
        }
    }

    public function destroy($id)
    {
        //
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();
        if ($siswa) {
            return redirect()->route('siswa.index')->with(['success' => 'Data Berhasil Dihapus!']);
        } else {
            return redirect()->route('siswa.index')->with(['error' => 'Data Gagal Dihapus!']);
        }
    }
}
