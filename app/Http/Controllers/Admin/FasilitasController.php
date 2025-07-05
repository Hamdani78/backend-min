<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use Illuminate\Http\Request;

class FasilitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fasilitas = Fasilitas::latest()->paginate(10);
        return view('admin.table.fasilitas.index', compact('fasilitas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.table.fasilitas.tambah');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $fasilitas = Fasilitas::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);
        if ($fasilitas) {
            return redirect()->route('fasilitas.index')->with(['succes' => 'Data Berhasil Disimpan']);
        } else {
            return redirect()->route('fasilitas.index')->with(['error' => 'Data Gagal Disimpan']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $fasilitas = Fasilitas::findOrFail($id);
        return view('admin.table.fasilitas.show', compact('fasilitas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $fasilitas = Fasilitas::find($id);
        return view('admin.table.fasilitas.update', compact('fasilitas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $fasilitas = Fasilitas::findOrFail($id);
        if ($request) {
            $fasilitas->update([
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
            ]);
        } else {
            $fasilitas->update([
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
            ]);
        }
        if ($fasilitas) {
            return redirect()->route('fasilitas.index')->with(['success' => 'Data Berhasil Diubah!']);
        } else {
            return redirect()->route('fasilitas.index')->with(['error' => 'Data Gagal Diubah!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $fasilitas = Fasilitas::findOrFail($id);
        $fasilitas->delete();
        if ($fasilitas) {
            return redirect()->route('fasilitas.index')->with(['success' => 'Data Berhasil Dihapus!']);
        } else {
            return redirect()->route('fasilitas.index')->with(['error' => 'Data Gagal Dihapus!']);
        }
    }
}
