<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FasilitasImages;
use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // Mengimpor kelas File
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class FasilitasImagesController extends Controller
{
    public function index(int $fasilitasId)
    {
        $fasilitas = Fasilitas::findOrFail($fasilitasId);
        $fasilitasImages = FasilitasImages::where('fasilitas_id', $fasilitasId)->get();

        return Inertia::render('Admin/FasilitasImage/Index', [
            'fasilitas' => $fasilitas,
            'images' => $fasilitasImages,
        ]);
    }
    
    public function store(Request $request, int $fasilitasId)
    {
        $request->validate([
            'foto.*' => 'required|image|mimes:png,jpg,jpeg,webp|max:10240', // maksimum 10MB
        ]);

        $fasilitas = Fasilitas::findOrFail($fasilitasId);

        $imageData = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $key => $file) {
                if ($file->isValid()) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = $key . '-' . time() . '.' . $extension;

                    $path = "uploads/fasilitas/";

                    $file->move($path, $filename);

                    $imageData[] = [
                        'fasilitas_id' => $fasilitasId,
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
                FasilitasImages::insert($imageData);
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

    public function destroy(int $fasilitasImageId)
    {
        $fasilitasImages = FasilitasImages::findOrFail($fasilitasImageId);

        if (File::exists(public_path($fasilitasImages->foto))) {
            File::delete(public_path($fasilitasImages->foto));
        }

        $fasilitasImages->delete();

        return redirect()->back()->with('status', 'Foto telah terhapus');
    }
}
