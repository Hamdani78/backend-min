<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use App\Models\Content;
use App\Models\Setting; // <-- penting

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Inertia::share([
            'kamad' => function () {
                $row = Content::query()
                    ->select('title', 'body')
                    ->where('slug', 'kamad')
                    ->first();

                return $row ? [
                    'title' => $row->title,
                    'body'  => $row->body,
                ] : null;
            },

            'ppdb' => fn () => [
                'is_open'  => Setting::isPpdbOpen(),
                'open_at'  => Setting::get('ppdb_open_at'),
                'close_at' => Setting::get('ppdb_close_at'),
            ],
        ]);
    }
}
