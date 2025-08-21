<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Inertia\Inertia;

class PpdbInfoController extends Controller
{
    public function closed()
    {
        return Inertia::render('Landing/PpdbClosed', [
            'message' => Setting::ppdbBanner(),
            'open_at' => Setting::get('ppdb_open_at'),
            'close_at'=> Setting::get('ppdb_close_at'),
        ]);
    }
}