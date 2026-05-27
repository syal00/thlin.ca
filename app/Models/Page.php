<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'section',
        'meta_description',
        'excerpt',
        'body',
        'template',
        'sort_order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        $like = '%'.$term.'%';

        return $query->published()->where(function (Builder $q) use ($like) {
            $q->where('title', 'like', $like)
                ->orWhere('excerpt', 'like', $like)
                ->orWhere('body', 'like', $like)
                ->orWhere('meta_description', 'like', $like);
        });
    }

    public function url(): string
    {
        if ($this->slug === 'home') {
            return url('/');
        }

        if ($this->slug === 'contact') {
            return route('contact');
        }

        return route('pages.show', ['section' => $this->section, 'page' => $this->slug]);
    }
}
