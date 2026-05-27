<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class NewsPost extends Model
{
    protected $fillable = [
        'slug', 'title', 'published_at', 'location', 'excerpt', 'body', 'image', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'date',
            'is_published' => 'boolean',
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
                ->orWhere('location', 'like', $like);
        });
    }

    public function url(): string
    {
        return route('news.show', $this);
    }
}
