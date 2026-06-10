<?php

namespace App\Http\Controllers;

use App\Models\BoardMember;
use App\Models\Career;
use App\Models\NewsPost;
use App\Models\Page;
use App\Models\PortfolioItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        $page = Page::published()
            ->where('slug', 'home')
            ->firstOrFail();

        $featuredPortfolio = PortfolioItem::featured()
            ->ordered()
            ->get();

        return view('pages.home', compact('page', 'featuredPortfolio'));
    }

    public function show(Request $request, string $section, Page $page): View
    {
        abort_unless($page->is_published && $page->section === $section, 404);

        $data = compact('page', 'section');

        return match ($page->template) {
            'portfolio' => view('pages.portfolio', array_merge($data, [
                'featured' => PortfolioItem::featured()->ordered()->get(),
                'past' => PortfolioItem::where('featured', false)->ordered()->get(),
            ])),

            'board' => view('pages.board', array_merge($data, [
                'members' => BoardMember::ordered()->get(),
            ])),

            'news' => view('pages.news', array_merge($data, [
                'posts' => NewsPost::published()->orderByDesc('published_at')->get(),
            ])),

            'careers' => view('pages.careers', array_merge($data, [
                'jobs' => Career::active()->orderByDesc('posted_at')->get(),
            ])),

            default => view('pages.show', $data),
        };
    }
    }