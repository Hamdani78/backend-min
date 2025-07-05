<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanImages extends Model
{
    use HasFactory;

    
    protected $table = 'kegiatans_images';

    protected $fillable = [
        'kegiatans_id',
        'foto'
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
}
