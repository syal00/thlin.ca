<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        $builtInPages = Page::builtIn()
            ->orderBy('section')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        $customPages = Page::custom()
            ->with('parent')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        return view('admin.pages.index', compact('builtInPages', 'customPages'));
    }

    public function create(): View
    {
        $page = new Page([
            'page_type' => 'custom',
            'status' => 'draft',
            'section' => 'custom',
            'template' => 'standard',
        ]);

        $publishedPages = Page::published()
            ->orderBy('title')
            ->get();

        $parentPages = Page::published()
            ->orderBy('title')
            ->get();

        return view('admin.pages.create', compact('page', 'publishedPages', 'parentPages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:pages,slug'],
            'parent_id' => ['nullable', 'exists:pages,id'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'show_in_navigation' => ['nullable', 'boolean'],
            'navigation_label' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
            'action' => ['nullable', 'string'],
        ]);

        $slug = $validated['slug'] ?? null;
        $validated['slug'] = $slug ? Str::slug($slug) : Str::slug($validated['title']);
        $validated['page_type'] = 'custom';
        $validated['section'] = 'custom';
        $validated['template'] = 'standard';
        $validated['status'] = $request->input('action') === 'publish' ? 'published' : 'draft';
        $validated['is_published'] = $validated['status'] === 'published';
        $validated['published_at'] = $validated['status'] === 'published' ? now() : null;
        $validated['show_in_navigation'] = $request->boolean('show_in_navigation');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['parent_id'] = $this->resolveParentId($request->input('parent_id'));

        Page::create($validated);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Custom page created successfully.');
    }

    public function edit(Page $page): View
    {
        $publishedPages = Page::published()
            ->where('id', '!=', $page->id)
            ->orderBy('title')
            ->get();

        $parentPages = Page::published()
            ->where('id', '!=', $page->id)
            ->orderBy('title')
            ->get();

        return view('admin.pages.edit', compact('page', 'publishedPages', 'parentPages'));
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ];

        if ($page->isCustom()) {
            $rules = array_merge($rules, [
                'slug' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('pages', 'slug')->ignore($page->id),
                ],
                'parent_id' => ['nullable', 'exists:pages,id'],
                'show_in_navigation' => ['nullable', 'boolean'],
                'navigation_label' => ['nullable', 'string', 'max:255'],
                'sort_order' => ['nullable', 'integer'],
                'action' => ['nullable', 'string'],
            ]);
        } else {
            $rules['excerpt'] = ['nullable', 'string'];
        }

        $validated = $request->validate($rules);

        if ($page->isBuiltIn()) {
            $validated['page_type'] = 'built_in';
            $validated['status'] = 'published';
            $validated['is_published'] = true;
        }

        if ($page->isCustom()) {
            if ((int) $request->input('parent_id') === $page->id) {
                throw ValidationException::withMessages([
                    'parent_id' => 'A page cannot be its own parent.',
                ]);
            }

            $validated['slug'] = Str::slug($validated['slug']);
            $validated['parent_id'] = $this->resolveParentId($request->input('parent_id'), $page);

            if ($request->input('action') === 'publish') {
                $validated['status'] = 'published';
                $validated['is_published'] = true;
                $validated['published_at'] = $page->published_at ?? now();
            }

            if ($request->input('action') === 'draft') {
                $validated['status'] = 'draft';
                $validated['is_published'] = false;
            }

            if (! $request->filled('action')) {
                $validated['status'] = $page->status;
                $validated['is_published'] = $page->status === 'published';
            }

            $validated['show_in_navigation'] = $request->boolean('show_in_navigation');
            $validated['sort_order'] = $validated['sort_order'] ?? 0;
        }

        $page->update($validated);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Page saved successfully.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        if ($page->isBuiltIn()) {
            return back()->with('error', 'Built-in pages cannot be deleted.');
        }

        $page->delete();

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Custom page deleted successfully.');
    }

    public function publish(Page $page): RedirectResponse
    {
        if ($page->isBuiltIn()) {
            return back()->with('error', 'Built-in pages are always published.');
        }

        $page->update([
            'status' => 'published',
            'is_published' => true,
            'published_at' => $page->published_at ?? now(),
        ]);

        return back()->with('success', 'Page published successfully.');
    }

    public function unpublish(Page $page): RedirectResponse
    {
        if ($page->isBuiltIn()) {
            return back()->with('error', 'Built-in pages cannot be unpublished.');
        }

        $page->update([
            'status' => 'draft',
            'is_published' => false,
        ]);

        return back()->with('success', 'Page moved to draft.');
    }

    private function resolveParentId(mixed $parentId, ?Page $page = null): ?int
    {
        if (! $parentId) {
            return null;
        }

        $parentId = (int) $parentId;

        if ($page && $parentId === $page->id) {
            throw ValidationException::withMessages([
                'parent_id' => 'A page cannot be its own parent.',
            ]);
        }

        $parent = Page::published()->find($parentId);

        if (! $parent) {
            throw ValidationException::withMessages([
                'parent_id' => 'Please choose a published parent page.',
            ]);
        }

        if ($page && $this->createsCircularParent($page, $parent)) {
            throw ValidationException::withMessages([
                'parent_id' => 'This parent page would create a circular page hierarchy.',
            ]);
        }

        return $parentId;
    }

    private function createsCircularParent(Page $page, Page $proposedParent): bool
    {
        $currentId = $proposedParent->parent_id;

        while ($currentId) {
            if ($currentId === $page->id) {
                return true;
            }

            $currentId = Page::whereKey($currentId)->value('parent_id');
        }

        return false;
    }
}
