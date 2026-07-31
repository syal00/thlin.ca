<?php

namespace App\Models;

use App\Support\FullTextSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'hero_title',
        'hero_subtitle',
        'body',
        'custom_html',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'parent_id',
        'page_type',
        'status',
        'show_in_navigation',
        'navigation_label',
        'sort_order',
        'published_at',
        'section',
        'excerpt',
        'template',
        'is_published',
        'created_by',
        'updated_by',
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

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function lastEditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
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

    public function scopeParentCandidates(Builder $query): Builder
    {
        return $query->published()
            ->where(function (Builder $q) {
                $q->where(function (Builder $builtIn) {
                    $builtIn->whereNull('parent_id')
                        ->whereIn('slug', [
                            'products-services',
                            'partners',
                            'about',
                            'contact',
                            'careers',
                            'board',
                            'news',
                            'portfolio',
                        ]);
                })->orWhere(function (Builder $custom) {
                    $custom->custom();
                });
            })
            ->orderBy('title');
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
        return FullTextSearch::apply(
            $query->published(),
            $term,
            ['title', 'excerpt', 'body', 'custom_html', 'meta_title', 'meta_description', 'hero_title', 'hero_subtitle', 'meta_keywords']
        );
    }

    public function getFullUrlAttribute(): string
    {
        if ($this->isBuiltIn()) {
            return $this->builtInPath();
        }

        if ($this->parent_id) {
            $parent = $this->relationLoaded('parent') ? $this->parent : $this->parent()->first();

            if ($parent) {
                return rtrim($parent->full_url, '/').'/'.ltrim($this->slug, '/');
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
        return url($this->full_url);
    }

    private function builtInPath(): string
    {
        if ($this->slug === 'home') {
            return '/';
        }

        if ($this->slug === 'contact') {
            return '/contact';
        }

        if (in_array($this->slug, ['products-services', 'partners', 'about'], true)) {
            return '/'.$this->slug;
        }

        if (in_array($this->section, ['products', 'partners', 'about'], true)) {
            return '/'.$this->section.'/'.ltrim($this->slug, '/');
        }

        return '/'.ltrim($this->slug, '/');
    }
}
