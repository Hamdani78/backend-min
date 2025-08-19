<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Pendaftar extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama','user_id',
        'tempat_lahir',
        'tanggal_lahir',
        'nik',
        'no_kk',
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
        'pra_sekolah',
        'nama_pra_sekolah',
        'kip_nama',
        'kip_nomor',
        'imunisasi',
        'foto',
        'is_verified',
        'verified_at',
        'verified_by',
        'verification_note',

        'status_pendaftaran',
        'wawancara_at',
        'wawancara_tempat',
        'wawancara_catatan',

        'nilai_spk', 'status_lulus', 
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'imunisasi'   => 'array',
        'wawancara_at' => 'datetime',
    ];

    // ========= Relasi =========
    public function orangTuas()
    {
        return $this->hasMany(OrangTua::class);
    }
    public function ayah()
    {
        return $this->hasOne(OrangTua::class)->where('tipe', 'Ayah');
    }
    public function ibu()
    {
        return $this->hasOne(OrangTua::class)->where('tipe', 'Ibu');
    }
    public function wali()
    {
        return $this->hasOne(Wali::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function spkNilai()
    {
        return $this->hasOne(SpkNilai::class, 'pendaftar_id', 'id');
    }

    // Relasi berkas (karena dipakai di booted())
    public function berkas()
    {
        return $this->hasOne(BerkasPendaftaran::class);
    }
    public function daftarUlang()
    {
        return $this->hasOne(\App\Models\DaftarUlang::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(Admin::class, 'verified_by');
    }

    // ========= Scopes & Helper =========
    public function scopeVerified($q)
    {
        return $q->where('is_verified', true);
    }
    public function scopePending($q)
    {
        return $q->where('is_verified', false);
    }

    public function getIsLockedAttribute(): bool
    {
        return (bool) $this->is_verified;
    }

    public function getIsLockedForEditingAttribute(): bool
    {
        return in_array($this->status_pendaftaran, [
            'berkas_terverifikasi',
            'wawancara',
            'pengumuman'
        ], true);
    }

    // ========= Lifecycle hooks =========
    protected static function booted()
    {
        static::deleting(function (Pendaftar $p) {
            if ($p->foto) {
                Storage::disk('public')->delete($p->foto);
            }
            $p->berkas()?->delete();
            $p->orangTuas()->delete();
            $p->wali()?->delete();
        });
    }
}
