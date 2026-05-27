<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewsPostController extends Controller
{
    public function index(): View
    {
        return view('admin.news.index', ['posts' => NewsPost::orderByDesc('published_at')->get()]);
    }

    public function create(): View
    {
        return view('admin.news.form', ['post' => new NewsPost]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['title']);

        NewsPost::create($data);

        return redirect()->route('admin.news.index')->with('status', 'News post created.');
    }

    public function edit(NewsPost $news): View
    {
        return view('admin.news.form', ['post' => $news]);
    }

    public function update(Request $request, NewsPost $news): RedirectResponse
    {
        $news->update($this->validated($request));

        return redirect()->route('admin.news.index')->with('status', 'News post updated.');
    }

    public function destroy(NewsPost $news): RedirectResponse
    {
        $news->delete();

        return redirect()->route('admin.news.index')->with('status', 'News post deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
        ]);

        $data['is_published'] = $request->boolean('is_published');

        return $data;
    }
}
