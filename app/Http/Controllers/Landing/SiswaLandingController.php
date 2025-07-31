<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Siswa;

class SiswaLandingController extends Controller
{
    public function index()
    {
        $data = Siswa::with('pegawai:id,nama')
            ->select('id', 'kelas', 'jml_siswa', 'laki_laki', 'perempuan', 'pegawai_id')
            ->get()
            ->map(function ($s) {
                return [
                    'className'     => $s->kelas,
                    'studentCount'  => $s->jml_siswa,
                    'maleCount'     => $s->laki_laki,
                    'femaleCount'   => $s->perempuan,
                    'teacherName'   => $s->pegawai->nama ?? '-',
                ];
            });

        return response()->json($data);
    }
}