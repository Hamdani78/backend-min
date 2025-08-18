<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ContentApiController extends Controller
{
    public function index()
    {
        return Content::select('id','slug','title','updated_at')
            ->orderBy('slug')
            ->get();
    }

    public function show(string $slug)
    {
        $content = Cache::remember("content:$slug", 60, function () use ($slug) {
            return Content::where('slug', $slug)
                ->select('slug','title','body','updated_at')
                ->first();
        });

        if (!$content) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($content);
    }
}
