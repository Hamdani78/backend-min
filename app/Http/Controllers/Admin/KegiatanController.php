<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kegiatan = Kegiatan::latest()->paginate(10);
        return view('admin.table.kegiatan.index', compact('kegiatan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.table.kegiatan.tambah');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $kegiatan = Kegiatan::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);
        if ($kegiatan) {
            return redirect()->route('kegiatan.index')->with(['succes' => 'Data Berhasil Disimpan']);
        } else {
            return redirect()->route('kegiatan.index')->with(['error' => 'Data Gagal Disimpan']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $kegiatan = Kegiatan::findOrFail($id);
        return view('admin.table.kegiatan.show', compact('kegiatan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kegiatan = Kegiatan::find($id);
        return view('admin.table.kegiatan.update', compact('kegiatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        if ($request) {
            $kegiatan->update([
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
            ]);
        } else {
            $kegiatan->update([
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
            ]);
        }
        if ($kegiatan) {
            return redirect()->route('kegiatan.index')->with(['success' => 'Data Berhasil Diubah!']);
        } else {
            return redirect()->route('kegiatan.index')->with(['error' => 'Data Gagal Diubah!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->delete();
        if ($kegiatan) {
            return redirect()->route('kegiatan.index')->with(['success' => 'Data Berhasil Dihapus!']);
        } else {
            return redirect()->route('kegiatan.index')->with(['error' => 'Data Gagal Dihapus!']);
        }
    }
}

