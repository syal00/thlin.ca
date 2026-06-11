<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\View\View;

class CustomPageController extends Controller
{
    public function show(string $slug): View
    {
        $page = Page::custom()
            ->published()
            ->where('slug', $slug)
            ->whereNull('parent_id')
            ->with('parent')
            ->firstOrFail();

        return view('pages.custom-show', compact('page'));
    }

    public function showChild(string $parentSlug, string $childSlug): View
    {
        $parent = Page::published()
            ->where('slug', $parentSlug)
            ->first();

        if ($parent) {
            $page = Page::custom()
                ->published()
                ->where('slug', $childSlug)
                ->where('parent_id', $parent->id)
                ->with('parent')
                ->first();

            if ($page) {
                return view('pages.custom-show', compact('page'));
            }
        }

        if (in_array($parentSlug, ['products', 'partners', 'about'], true)) {
            $builtInPage = Page::published()
                ->builtIn()
                ->where('slug', $childSlug)
                ->where('section', $parentSlug)
                ->first();

            if ($builtInPage) {
                return app(PageController::class)->show(request(), $parentSlug, $builtInPage);
            }
        }

        abort(404);
    }
}
