<?php

namespace App\Models;

use App\Support\FullTextSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    protected $fillable = [
        'title', 'slug', 'location', 'employment_type', 'posted_at', 'closes_at', 'body', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'posted_at' => 'date',
            'closes_at' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return FullTextSearch::apply(
            $query->active(),
            $term,
            ['title', 'body', 'location']
        );
    }
}
