<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoardMember;
use App\Models\Career;
use App\Models\NewsPost;
use App\Models\Page;
use App\Models\PortfolioItem;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'pageCount' => Page::count(),
            'newsCount' => NewsPost::count(),
            'careerCount' => Career::count(),
            'boardCount' => BoardMember::count(),
            'portfolioCount' => PortfolioItem::count(),
            'userCount' => User::count(),
            'recentPages' => Page::query()
                ->orderByDesc('updated_at')
                ->limit(8)
                ->get(),
        ]);
    }
}
