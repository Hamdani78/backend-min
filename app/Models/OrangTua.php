<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrangTua extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'pendaftar_id',
        'tipe', // 'Ayah' atau 'Ibu'
        'nama',
        'status',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'pendidikan',
        'pekerjaan',
        'penghasilan',
        'no_hp',
        'tempat_tinggal',
        'alamat',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'kelurahan',
        'kks',
        'pkh'
    ];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }
}
