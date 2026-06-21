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

class AdminPageController extends Controller
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

        return view('admin.pages.create', [
            'page' => $page,
            'publishedPages' => $this->publishedPagesForLinks(),
            'parentPages' => $this->parentPageOptions(),
            'parentPageGroups' => $this->parentPageGroups(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:pages,slug'],
            'parent_id' => ['nullable', 'exists:pages,id'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'show_in_navigation' => ['nullable', 'boolean'],
            'navigation_label' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
            'action' => ['nullable', 'string'],
        ], $this->validationMessages());

        $slug = $validated['slug'];
        $validated['slug'] = Str::slug($slug);
        $validated['page_type'] = 'custom';
        $validated['section'] = 'custom';
        $validated['template'] = 'standard';
        $validated['status'] = $request->input('action') === 'publish' ? 'published' : 'draft';
        $validated['is_published'] = $validated['status'] === 'published';
        $validated['published_at'] = $validated['status'] === 'published' ? now() : null;
        $validated['show_in_navigation'] = $request->boolean('show_in_navigation');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['parent_id'] = $this->resolveParentId($request->input('parent_id'));
        $validated['created_by'] = $request->user()->id;
        $validated['updated_by'] = $request->user()->id;

        Page::create($validated);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Custom page created successfully.');
    }

    public function edit(Page $page): View
    {
        $page->load('parent');

        return view('admin.pages.edit', [
            'page' => $page,
            'publishedPages' => $this->publishedPagesForLinks($page),
            'parentPages' => $this->parentPageOptions($page),
            'parentPageGroups' => $this->parentPageGroups($page, $page),
        ]);
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

        $validated = $request->validate($rules, $this->validationMessages());

        if ($page->isBuiltIn()) {
            $validated['page_type'] = 'built_in';
            $validated['status'] = 'published';
            $validated['is_published'] = true;
            $validated['parent_id'] = null;
        }

        if ($page->isCustom()) {
            if ((int) $request->input('parent_id') === $page->id) {
                throw ValidationException::withMessages([
                    'parent_id' => 'A page cannot be placed inside itself.',
                ]);
            }

            $validated['slug'] = Str::slug($validated['slug']);
            $validated['parent_id'] = $this->resolveParentId($request->input('parent_id'), $page);

            $action = $request->input('action');

            if ($action === 'publish') {
                $validated['status'] = 'published';
                $validated['is_published'] = true;
                $validated['published_at'] = $page->published_at ?? now();
            } elseif ($action === 'draft') {
                $validated['status'] = 'draft';
                $validated['is_published'] = false;
            } else {
                $validated['status'] = $page->status;
                $validated['is_published'] = $page->status === 'published';
            }

            $validated['show_in_navigation'] = $request->boolean('show_in_navigation');
            $validated['sort_order'] = array_key_exists('sort_order', $validated)
                ? ($validated['sort_order'] ?? 0)
                : ($page->sort_order ?? 0);
            $validated['navigation_label'] = $request->input('navigation_label');
        }

        if (array_key_exists('body', $validated) && blank($validated['body']) && filled($page->body)) {
            unset($validated['body']);
        }

        $validated['updated_by'] = $request->user()->id;

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
            'updated_by' => $request->user()->id,
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
            'updated_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Page moved to draft.');
    }

    private function publishedPagesForLinks(?Page $exclude = null)
    {
        return Page::published()
            ->with('parent')
            ->when($exclude, fn ($query) => $query->where('id', '!=', $exclude->id))
            ->orderBy('title')
            ->get();
    }

    private function parentPageOptions(?Page $exclude = null)
    {
        return Page::parentCandidates()
            ->when($exclude, fn ($query) => $query->where('id', '!=', $exclude->id))
            ->get();
    }

    /** @return array{main: \Illuminate\Support\Collection, other: \Illuminate\Support\Collection, custom: \Illuminate\Support\Collection, current: \Illuminate\Support\Collection} */
    private function parentPageGroups(?Page $exclude = null, ?Page $editing = null): array
    {
        $pages = $this->parentPageOptions($exclude);
        $mainSlugs = ['products-services', 'partners', 'about', 'contact'];
        $otherSlugs = ['careers', 'board', 'news', 'portfolio'];

        $groups = [
            'main' => $pages->filter(fn (Page $page) => in_array($page->slug, $mainSlugs, true))->values(),
            'other' => $pages->filter(fn (Page $page) => in_array($page->slug, $otherSlugs, true))->values(),
            'custom' => $pages->filter(fn (Page $page) => $page->isCustom())->values(),
            'current' => collect(),
        ];

        if ($editing?->parent_id) {
            $currentParent = $editing->relationLoaded('parent')
                ? $editing->parent
                : $editing->parent()->first();
            $listedIds = $groups['main']->merge($groups['other'])->merge($groups['custom'])->pluck('id');

            if ($currentParent && ! $listedIds->contains($currentParent->id)) {
                $groups['current'] = collect([$currentParent]);
            }
        }

        return $groups;
    }

    /** @return array<string, string> */
    private function validationMessages(): array
    {
        return [
            'title.required' => 'Please enter a page name.',
            'slug.required' => 'Please enter a page link.',
            'slug.unique' => 'This page link is already used. Please choose another one.',
            'parent_id.exists' => 'Please choose a valid section for this page.',
        ];
    }

    private function resolveParentId(mixed $parentId, ?Page $page = null): ?int
    {
        if (! $parentId) {
            return null;
        }

        $parentId = (int) $parentId;

        if ($page && $parentId === $page->id) {
            throw ValidationException::withMessages([
                'parent_id' => 'A page cannot be placed inside itself.',
            ]);
        }

        $parent = Page::parentCandidates()->find($parentId);

        if (! $parent) {
            throw ValidationException::withMessages([
                'parent_id' => 'Please choose a valid parent page.',
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
