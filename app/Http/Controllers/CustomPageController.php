<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewResponse;

class CustomPageController extends Controller
{
    public function show(string $slug): ViewResponse|RedirectResponse
    {
        $page = Page::custom()
            ->published()
            ->where('slug', $slug)
            ->whereNull('parent_id')
            ->with(['parent.visibleChildren'])
            ->first();

        if ($page) {
            View::share('cmsPage', $page);

            return view('pages.custom-show', compact('page'));
        }

        // Built-in top-level pages: section landing pages (products-services,
        // partners) live at /{slug}; other built-ins canonically live at
        // /{section}/{slug}, so redirect there instead of serving duplicates.
        $builtIn = Page::builtIn()
            ->published()
            ->whereNull('parent_id')
            ->where('slug', $slug)
            ->first();

        if (! $builtIn) {
            abort(404);
        }

        if ($builtIn->full_url !== '/'.$slug) {
            return redirect($builtIn->full_url, 301);
        }

        return app(PageController::class)->show(request(), $builtIn->section, $builtIn);
    }

    public function showChild(string $parentSlug, string $childSlug): ViewResponse
    {
        $parent = Page::published()
            ->where('slug', $parentSlug)
            ->first();

        if ($parent) {
            $page = Page::custom()
                ->published()
                ->where('slug', $childSlug)
                ->where('parent_id', $parent->id)
                ->with(['parent.visibleChildren'])
                ->first();

            if ($page) {
                View::share('cmsPage', $page);

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
