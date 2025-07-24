<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable= ['kelas', 'laki_laki', 'perempuan', 'jml_siswa', 'pegawai_id'];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
