<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpkNilai extends Model
{
    protected $table = 'spk_nilai';
    protected $fillable = ['pendaftar_id', 'umur', 'zonasi', 'berkas', 'wawancara'];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }
}