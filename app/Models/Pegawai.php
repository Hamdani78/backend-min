<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Siswa;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawais';
    protected $fillable = ['nama', 'nip', 'email', 'status', 'foto', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }
}
