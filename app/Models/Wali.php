<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wali extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'pendaftar_id',
        'nama',
        'hubungan_keluarga'
    ];


    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }
}
