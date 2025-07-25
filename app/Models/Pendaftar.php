<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pendaftar extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'no_kk',
        'nik',
        'anak_ke',
        'jumlah_saudara',
        'jenis_kelamin',
        'agama',
        'berat_badan',
        'tinggi_badan',
        'cita_cita',
        'hobi',
        'bahasa',
        'keadaan_jasmani',
        'alamat',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'kelurahan',
        'no_hp',
        'tinggal_dengan',
        'pembiaya',
        'jarak_ke_madrasah',
        'kebutuhan_khusus',
        'kebutuhan_disabilitas',
        'imunisasi',
        'pra_sekolah',
        'nama_pra_sekolah',
        'kip_nama',
        'kip_nomor',
        'foto'
    ];

    public function orangTuas()
    {
        return $this->hasMany(OrangTua::class);
    }

    public function wali()
    {
        return $this->hasOne(Wali::class);
    }
}
