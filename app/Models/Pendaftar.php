<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pendaftar extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'user_id',
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
        'foto',
        'status_pendaftaran',
        'nilai_spk',
        'status_lulus',
    ];

    public function orangTuas()
    {
        return $this->hasMany(OrangTua::class);
    }

    public function wali()
    {
        return $this->hasOne(Wali::class);
    }

    public function berkas()
    {
        return $this->hasOne(BerkasPendaftaran::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function spkNilai()
    {
        return $this->hasOne(SpkNilai::class);
    }

    public function daftarUlang()
    {
        return $this->hasOne(DaftarUlang::class);
    }

    protected $casts = [
        'imunisasi' => 'array',
        'orang_tuas' => 'array',
        'wali' => 'array',
    ];

    protected static function booted()
    {
        static::deleting(function ($pendaftar) {
            if ($pendaftar->berkas) {
                $pendaftar->berkas->delete();
            }

            $pendaftar->orangTuas()->delete();
            $pendaftar->wali()->delete();
        });
    }
}
