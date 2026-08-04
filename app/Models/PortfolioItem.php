<?php

namespace App\Models;

use App\Support\FullTextSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PortfolioItem extends Model
{
    protected $fillable = ['title', 'excerpt', 'url', 'image', 'featured', 'sort_order'];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return FullTextSearch::apply($query, $term, ['title', 'excerpt']);
    }

    public function imageUrl(): ?string
    {
        if (! $this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        if (str_starts_with($this->image, 'images/')) {
            return asset($this->image);
        }

        return asset('storage/'.$this->image);
    }
}
