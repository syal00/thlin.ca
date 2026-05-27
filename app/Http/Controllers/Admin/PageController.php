<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        $pages = Page::orderBy('section')->orderBy('sort_order')->orderBy('title')->get();

        return view('admin.pages.index', compact('pages'));
    }

    public function edit(Page $page): View
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'excerpt' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'is_published' => ['sometimes', 'boolean'],
        ]);

        $data['is_published'] = $request->boolean('is_published');

        $page->update($data);

        return redirect()
            ->route('admin.pages.index')
            ->with('status', 'Page updated successfully.');
    }
}
