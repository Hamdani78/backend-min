<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;
    
    protected $fillable = ['nama', 'nip', 'email', 'status', 'foto'];

    public function siswa()
    {
        return $this->hasOne('Siswa::class');
    }
}

