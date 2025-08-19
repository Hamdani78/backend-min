<?php

namespace App\Services;

use App\Models\Pendaftar;
use App\Models\SpkNilai;
use Illuminate\Support\Carbon;

class SpkAuto
{
    public static function scoreUmur(?string $tgl): float
    {
        if (!$tgl) return 0.0;
        try { $age = Carbon::parse($tgl)->age; } catch (\Throwable) { return 0.0; }
        return $age >= 6 ? 0.5 : 0.0;
    }

    public static function scoreZonasi($jarak): float
    {
        if ($jarak === null || $jarak === '') return 0.0;
        $km = (float) $jarak;
        if ($km < 1)  return 0.3;
        if ($km <= 2) return 0.2;
        return 0.1;
    }

    public static function scoreBerkas($berkas): float
    {
        if (!$berkas) return 0.0;
        $wajib = [
            (bool) $berkas->ijazah_tk,
            (bool) $berkas->akte_kelahiran,
            (bool) $berkas->kartu_keluarga,
        ];
        $ada = collect($wajib)->filter()->count();
        return match ($ada) { 3 => 0.15, 2 => 0.10, default => 0.05 };
    }

    public static function sync(Pendaftar $p): SpkNilai
    {
        $umur   = self::scoreUmur($p->tanggal_lahir ?? null);
        $zonasi = self::scoreZonasi($p->jarak_ke_madrasah ?? null);
        $berkas = self::scoreBerkas($p->berkas ?? null);

        $rec = $p->spkNilai()->first();

        if (!$rec) {
            $rec = $p->spkNilai()->create([
                'umur'       => $umur,
                'zonasi'     => $zonasi,
                'berkas'     => $berkas,
                'wawancara'  => 0.0,
            ]);
        } else {
            $rec->fill([
                'umur'   => $umur,
                'zonasi' => $zonasi,
                'berkas' => $berkas,
            ])->save();
        }

        $p->update([
            'nilai_spk' => (float) $rec->umur + (float) $rec->zonasi + (float) $rec->berkas + (float) $rec->wawancara,
        ]);

        return $rec->refresh();
    }

    public static function setWawancara(Pendaftar $p, float $wawancara): SpkNilai
    {
        $rec = self::sync($p);

        $rec->wawancara = $wawancara;
        $rec->save();

        $p->update([
            'nilai_spk' => (float) $rec->umur + (float) $rec->zonasi + (float) $rec->berkas + (float) $rec->wawancara,
        ]);

        return $rec->refresh();
    }
}
