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
        $wajib = [(bool)$berkas->ijazah_tk,(bool)$berkas->akte_kelahiran,(bool)$berkas->kartu_keluarga];
        $ada = collect($wajib)->filter()->count();
        return match ($ada) { 3 => 0.15, 2 => 0.10, default => 0.05 };
    }

    public static function sync(Pendaftar $p): SpkNilai
    {
        $umur   = self::scoreUmur($p->tanggal_lahir ?? null);
        $zonasi = self::scoreZonasi($p->jarak_ke_madrasah ?? null);
        $berkas = self::scoreBerkas($p->berkas ?? null);

        $rec = $p->spkNilai()->firstOrNew([]);
        $wawancara = $rec->wawancara ?? 0.0;

        $rec->fill(compact('umur','zonasi','berkas'));
        $rec->pendaftar_id = $p->id;
        $rec->save();

        $p->update(['nilai_spk' => $umur + $zonasi + $berkas + (float)$wawancara]);

        return $rec->refresh();
    }

    public static function setWawancara(Pendaftar $p, float $wawancara): SpkNilai
    {
        $rec = $p->spkNilai()->firstOrCreate(['pendaftar_id' => $p->id]);
        $rec->wawancara = $wawancara;
        $rec->save();

        $auto = self::sync($p);
        if ($auto->wawancara != $wawancara) {
            $auto->wawancara = $wawancara;
            $auto->save();
            $p->update(['nilai_spk' => $auto->umur + $auto->zonasi + $auto->berkas + $wawancara]);
        }
        return $auto->refresh();
    }
}
