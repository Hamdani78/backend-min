<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use App\Models\Content;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }
    public function boot(): void
    {
        Inertia::share('kamad', function () {
            return Content::query()
                ->select('title', 'body')
                ->where('slug', 'kamad')
                ->first();
        });
    }
}
