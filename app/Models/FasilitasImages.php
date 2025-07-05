<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FasilitasImages extends Model
{
    use HasFactory;

    protected $table = 'fasilitas_images';

    protected $fillable = [
        'fasilitas_id',
        'foto'
    ];

    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class);
    }
}
