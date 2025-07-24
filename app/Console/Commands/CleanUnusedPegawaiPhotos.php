<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Models\Pegawai;

class CleanUnusedPegawaiPhotos extends Command
{
    protected $signature = 'pegawai:clean-photos';
    protected $description = 'Hapus foto pegawai yang tidak digunakan di tabel pegawai';

    public function handle()
    {
        $this->info("⏳ Mengecek folder foto pegawai...");

        $photoPath = public_path('storage/pegawai');
        $filesInDirectory = collect(File::files($photoPath))->map(function ($file) {
            return $file->getFilename();
        });

        $usedPhotos = Pegawai::pluck('foto')->filter()->map(function ($foto) {
            return basename($foto);
        });

        $unusedPhotos = $filesInDirectory->diff($usedPhotos);

        if ($unusedPhotos->isEmpty()) {
            $this->info("✅ Tidak ada foto yang perlu dihapus.");
            return;
        }

        foreach ($unusedPhotos as $file) {
            File::delete($photoPath . '/' . $file);
            $this->line("🗑️ Dihapus: $file");
        }

        $this->info("🎉 Selesai! Total file yang dihapus: " . $unusedPhotos->count());
    }
}
