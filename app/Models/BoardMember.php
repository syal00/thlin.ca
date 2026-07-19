<?php

namespace App\Models;

use App\Support\FullTextSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BoardMember extends Model
{
    protected $fillable = ['name', 'role', 'bio', 'photo', 'sort_order'];

    protected function casts(): array
    {
        return ['sort_order' => 'integer'];
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return FullTextSearch::apply($query, $term, ['name', 'role', 'bio']);
    }

    public function photoUrl(): ?string
    {
        if (! $this->photo) {
            return null;
        }

        if (str_starts_with($this->photo, 'http://') || str_starts_with($this->photo, 'https://')) {
            return $this->photo;
        }

        return asset('storage/'.$this->photo);
    }
}
