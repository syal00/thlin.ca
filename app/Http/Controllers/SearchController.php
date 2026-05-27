<?php

namespace App\Http\Controllers;

use App\Services\SiteSearch;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __construct(private SiteSearch $siteSearch) {}

    public function index(Request $request): View
    {
        $query = trim((string) $request->input('q', ''));
        $results = $this->siteSearch->search($query);

        return view('search.results', compact('query', 'results'));
    }
}
