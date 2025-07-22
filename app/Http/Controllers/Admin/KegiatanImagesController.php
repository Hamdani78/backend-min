<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\KegiatanImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // Mengimpor kelas File
use Illuminate\Support\Facades\DB;

class KegiatanImagesController extends Controller
{

    public function index(int $kegiatanId)
    {
        $kegiatan = Kegiatan::findOrFail($kegiatanId);

        $kegiatanImages = KegiatanImages::where('kegiatans_id', $kegiatanId)->get();
        return view('admin.table.kegiatan-image.index', ['kegiatan' => $kegiatan, 'images' => $kegiatanImages]);
    }

    public function store(Request $request, int $kegiatanId)
    {
        $request->validate([
            'foto.*' => 'required|image|mimes:png,jpg,jpeg,webp|max:10240', // maksimum 10MB
        ]);

        $kegiatan = Kegiatan::findOrFail($kegiatanId);

        $imageData = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $key => $file) {
                if ($file->isValid()) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = $key . '-' . time() . '.' . $extension;

                    $path = "uploads/kegiatan/";

                    $file->move($path, $filename);

                    $imageData[] = [
                        'kegiatans_id' => $kegiatanId,
                        'foto' => $path . $filename,
                    ];
                } else {
                    return redirect()->back()->with('error', 'Gagal mengunggah satu atau lebih file. Silakan coba lagi.');
                }
            }
        }

        try {
            DB::beginTransaction();

            if (!empty($imageData)) {
                KegiatanImages::insert($imageData);
            } else {
                return redirect()->back()->with('error', 'Tidak ada gambar yang diunggah.');
            }

            DB::commit();

            return redirect()->back()->with('status', 'Gambar berhasil diunggah.');
        } catch (\Exception $e) {
            
            DB::rollBack();

            return redirect()->back()->with('error', 'Gagal menyimpan gambar. Silakan coba lagi.');
        }
    }

    public function destroy(int $kegiatanImageId)
    {
        $kegiatanImages = KegiatanImages::findOrFail($kegiatanImageId);

        if(File::exists(public_path($kegiatanImages->foto))){
            File::delete(public_path($kegiatanImages->foto));
        }

        $kegiatanImages->delete();

        return redirect()->back()->with('status', 'Foto telah terhapus');
    }
}