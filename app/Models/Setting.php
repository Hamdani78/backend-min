<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

/**
 * Tabel key-value sederhana:
 * - key   : string
 * - value : string (disimpan sebagai string)
 */
class Setting extends Model
{
    protected $table = 'settings';
    public $timestamps = true;
    protected $fillable = ['key', 'value'];

    /* =========================
     *  Util GET / SET + helpers
     * ========================= */

    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting:$key", 60, function () use ($key, $default) {
            $row = static::query()->where('key', $key)->first();
            return $row ? $row->value : $default;
        });
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => (string)($value ?? '')]);
        Cache::forget("setting:$key");
    }

    public static function getBool(string $key, bool $default = false): bool
    {
        $raw = static::get($key, $default ? '1' : '0');
        // treat '1', 'true', 'on', 'yes' as true
        return filter_var($raw, FILTER_VALIDATE_BOOL);
    }

    public static function getDate(string $key): ?Carbon
    {
        $v = static::get($key);
        return $v ? Carbon::parse($v) : null;
    }

    public static function ppdbBanner(): string
    {
        return (string) static::get('ppdb_banner', 'PPDB saat ini ditutup.');
    }

    /* =========================
     *   Status PPDB (SERVER)
     * =========================
     * Aturan:
     * - '1'   => Buka (manual, prioritas tertinggi)
     * - '0'   => Tutup (manual, prioritas tertinggi, tanggal diabaikan)
     * - 'auto'=> Ikuti tanggal ppdb_open_at..ppdb_close_at
     * - selain itu => default tutup
     */
    public static function isPpdbOpen(): bool
    {
        $manual = (string) static::get('ppdb_open', '0'); // '0' | '1' | 'auto'
        if ($manual === '1') return true;   // paksa buka
        if ($manual === '0') return false;  // paksa tutup

        if ($manual === 'auto') {
            $start = static::getDate('ppdb_open_at');
            $end   = static::getDate('ppdb_close_at');
            if ($start && $end) {
                $now = now();
                return $now->between($start->startOfDay(), $end->endOfDay());
            }
            return false; // tanggal tidak lengkap -> tutup
        }

        // fallback
        return false;
    }
}
