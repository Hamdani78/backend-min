<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'deskripsi'];

    public function images()
    {
        return $this->hasMany(KegiatanImages::class, 'kegiatans_id');
    }
}
