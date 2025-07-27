<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BerkasPendaftaran extends Model
{
    protected $table = 'berkas_pendaftarans';

    protected $fillable = [
        'pendaftar_id',
        'ijazah_tk',
        'akte_kelahiran',
        'kartu_keluarga',
        'kip',
    ];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }

    protected static function booted()
    {
        static::deleting(function ($berkas) {
            $files = array_filter([
                $berkas->ijazah_tk,
                $berkas->akte_kelahiran,
                $berkas->kartu_keluarga,
                $berkas->kip,
            ]);

            Storage::disk('public')->delete($files);
        });
    }
}
