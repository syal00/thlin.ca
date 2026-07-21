<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoardMember;
use App\Models\Career;
use App\Models\ContactMessage;
use App\Models\MediaFile;
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
            'totalPages' => Page::query()->count('*'),
            'publishedPages' => Page::published()->count('*'),
            'draftPages' => Page::query()->where('status', 'draft')->count('*'),
            'uploadedFiles' => MediaFile::query()->count('*'),
            'newsCount' => NewsPost::query()->count('*'),
            'careerCount' => Career::query()->count('*'),
            'messageCount' => ContactMessage::query()->count('*'),
            'recentPages' => Page::query()
                ->orderByDesc('updated_at')
                ->limit(8)
                ->get(),
            'publishedPagesList' => Page::published()->orderBy('title')->get(),
        ]);
    }
}
