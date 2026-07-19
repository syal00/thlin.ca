<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class FullTextSearch
{
    public static function supportsTsVector(): bool
    {
        return Schema::getConnection()->getDriverName() === 'pgsql';
    }

    public static function apply(Builder $query, string $term, array $likeColumns): Builder
    {
        if (self::supportsTsVector()) {
            return $query->whereRaw('search_vector @@ plainto_tsquery(?, ?)', ['english', $term]);
        }

        $like = '%'.$term.'%';

        return $query->where(function (Builder $q) use ($like, $likeColumns) {
            foreach ($likeColumns as $index => $column) {
                if ($index === 0) {
                    $q->where($column, 'like', $like);
                } else {
                    $q->orWhere($column, 'like', $like);
                }
            }
        });
    }
}
