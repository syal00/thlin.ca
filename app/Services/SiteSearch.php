<?php

namespace App\Services;

use App\Models\BoardMember;
use App\Models\Career;
use App\Models\NewsPost;
use App\Models\Page;
use App\Models\PortfolioItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SiteSearch
{
    public function search(string $term): Collection
    {
        $term = trim($term);

        if ($term === '') {
            return collect();
        }

        $results = collect();

        foreach (Page::search($term)->with('parent')->limit(20)->get() as $page) {
            $results->push([
                'type' => 'Page',
                'title' => $page->title,
                'excerpt' => $page->excerpt ?: Str::limit(strip_tags($page->body ?? ''), 160),
                'url' => url($page->full_url),
            ]);
        }

        foreach (NewsPost::search($term)->limit(10)->get() as $post) {
            $results->push([
                'type' => 'News',
                'title' => $post->title,
                'excerpt' => $post->excerpt,
                'url' => $post->url(),
            ]);
        }

        foreach (Career::search($term)->limit(10)->get() as $career) {
            $results->push([
                'type' => 'Career',
                'title' => $career->title,
                'excerpt' => strip_tags(substr($career->body ?? '', 0, 160)),
                'url' => route('pages.show', ['section' => 'about', 'page' => 'careers']).'#'. $career->slug,
            ]);
        }

        foreach (BoardMember::search($term)->limit(10)->get() as $member) {
            $results->push([
                'type' => 'Board',
                'title' => $member->name.' — '.$member->role,
                'excerpt' => strip_tags(substr($member->bio ?? '', 0, 160)),
                'url' => route('pages.show', ['section' => 'about', 'page' => 'board']),
            ]);
        }

        foreach (PortfolioItem::search($term)->limit(10)->get() as $item) {
            $results->push([
                'type' => 'Portfolio',
                'title' => $item->title,
                'excerpt' => $item->excerpt,
                'url' => $item->url ?: route('pages.show', ['section' => 'products', 'page' => 'portfolio']),
            ]);
        }

        return $results->sortBy('title')->values();
    }
}
