<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class KamadController extends Controller
{
    public function index()
    {
        $kamad = Content::where('slug', 'kamad')
            ->select('title', 'body')
            ->first();

        return Inertia::render('Landing/views/Presentation/PresentationView', [
            'kamad' => $kamad,
        ]);
    }
}
