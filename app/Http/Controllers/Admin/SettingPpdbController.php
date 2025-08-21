<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingPpdbController extends Controller
{
    public function edit()
    {
        return Inertia::render('Admin/Settings/Ppdb', [
            'ppdb_open'     => Setting::get('ppdb_open', '0'), // '0' | '1' | 'auto'
            'ppdb_open_at'  => Setting::get('ppdb_open_at'),
            'ppdb_close_at' => Setting::get('ppdb_close_at'),
            'ppdb_banner'   => Setting::ppdbBanner(),
        ]);
    }

    public function update(Request $req)
    {
        $data = $req->validate([
            'ppdb_open'     => ['required', 'in:0,1,auto'],
            'ppdb_open_at'  => ['nullable', 'date'],
            'ppdb_close_at' => ['nullable', 'date', 'after_or_equal:ppdb_open_at'],
            'ppdb_banner'   => ['required', 'string', 'max:255'],
        ]);

        foreach ($data as $k => $v) {
            Setting::set($k, (string)($v ?? ''));
        }

        // optional: bersihkan cache lain bila perlu
        // cache()->flush();

        return back()->with('success', 'Pengaturan PPDB diperbarui.');
    }
}
