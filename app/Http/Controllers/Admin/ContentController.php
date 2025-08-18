<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContentController extends Controller
{
    public function index()
    {
        $contents = Content::query()
            ->select('id', 'slug', 'title', 'updated_at')
            ->orderByDesc('updated_at')
            ->get();

        return Inertia::render('Admin/Content/Index', ['contents' => $contents]);
    }

    public function create()
    {
        return Inertia::render('Admin/Content/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug'  => ['required', 'string', 'max:100', 'unique:contents,slug', 'regex:/^[a-z0-9-]+$/'],
            'title' => ['required', 'string', 'max:255'],
            'body'  => ['required', 'string'],
        ], [
            'slug.regex' => 'Slug hanya boleh huruf kecil, angka, dan tanda minus (-).'
        ]);

        Content::create($validated);

        return redirect()->route('content.index')->with('success', 'Konten baru berhasil dibuat.');
    }

    public function edit(Content $content)
    {
        return Inertia::render('Admin/Content/Edit', ['content' => $content]);
    }

    public function update(Request $request, Content $content)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body'  => ['required', 'string'],
        ]);

        $content->update($validated);

        return redirect()->route('content.index')->with('success', 'Konten berhasil diperbarui.');
    }

    public function destroy(Content $content)
    {
        $content->delete();

        return redirect()->route('content.index')->with('success', 'Konten dihapus.');
    }
}
