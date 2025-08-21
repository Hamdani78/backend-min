<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'ppdb_open'     => '0',
            'ppdb_open_at'  => null,
            'ppdb_close_at' => null,
            'ppdb_banner'   => 'PPDB saat ini ditutup.',
        ];

        foreach ($defaults as $k => $v) {
            Setting::updateOrCreate(['key' => $k], ['value' => $v]);
        }
    }
}
