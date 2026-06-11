<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    protected $fillable = [
        'parent_id',
        'slug',
        'title',
        'section',
        'hero_title',
        'hero_subtitle',
        'meta_description',
        'excerpt',
        'body',
        'template',
        'sort_order',
        'is_published',
        'page_type',
        'status',
        'show_in_navigation',
        'navigation_label',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'show_in_navigation' => 'boolean',
            'sort_order' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Page::class, 'parent_id')
            ->where('status', 'published')
            ->orderBy('sort_order')
            ->orderBy('title');
    }

    public function visibleChildren(): HasMany
    {
        return $this->children()->where('show_in_navigation', true);
    }

    public function scopeBuiltIn(Builder $query): Builder
    {
        return $query->where('page_type', 'built_in');
    }

    public function scopeCustom(Builder $query): Builder
    {
        return $query->where('page_type', 'custom');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeNavigationItems(Builder $query): Builder
    {
        return $query
            ->custom()
            ->published()
            ->whereNull('parent_id')
            ->where('show_in_navigation', true)
            ->orderBy('sort_order')
            ->orderBy('title');
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        $like = '%'.$term.'%';

        return $query->published()->where(function (Builder $q) use ($like) {
            $q->where('title', 'like', $like)
                ->orWhere('excerpt', 'like', $like)
                ->orWhere('body', 'like', $like)
                ->orWhere('meta_description', 'like', $like)
                ->orWhere('hero_title', 'like', $like)
                ->orWhere('hero_subtitle', 'like', $like);
        });
    }

    public function getFullUrlAttribute(): string
    {
        if ($this->isBuiltIn()) {
            if ($this->slug === 'home') {
                return '/';
            }

            return '/'.ltrim($this->slug, '/');
        }

        if ($this->parent_id) {
            $parent = $this->relationLoaded('parent') ? $this->parent : $this->parent()->first();

            if ($parent) {
                return '/'.ltrim($parent->slug, '/').'/'.ltrim($this->slug, '/');
            }
        }

        return '/'.ltrim($this->slug, '/');
    }

    public function getMenuLabelAttribute(): string
    {
        return $this->navigation_label ?: $this->title;
    }

    public function getPublicUrlAttribute(): string
    {
        return $this->url();
    }

    public function isBuiltIn(): bool
    {
        return $this->page_type === 'built_in';
    }

    public function isCustom(): bool
    {
        return $this->page_type === 'custom';
    }

    public function url(): string
    {
        if ($this->isCustom()) {
            return url($this->full_url);
        }

        if ($this->slug === 'home') {
            return url('/');
        }

        if ($this->slug === 'contact') {
            return route('contact');
        }

        return route('pages.show', ['section' => $this->section, 'page' => $this->slug]);
    }
}
