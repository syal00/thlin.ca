<?php

namespace App\Http\Controllers;

use App\Models\NewsPost;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function show(NewsPost $news): View
    {
        abort_unless($news->is_published, 404);

        return view('news.show', ['post' => $news]);
    }
}
