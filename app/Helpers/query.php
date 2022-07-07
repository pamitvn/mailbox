<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

if (!function_exists('query_by_cols')) {
    function query_by_cols(Builder &$query, array $cols = [], array $params = []): Builder
    {
        foreach ($cols as $col) {
            $value = \Illuminate\Support\Arr::get($params, $col);
            $query = $query->when(filled($value), fn($query) => $query->where($col, $value));
        }
        return $query;
    }
}

if (!function_exists('query_relation_by_cols')) {
    function query_relation_by_cols(Builder &$query, array $relations = [], array $params = []): Builder
    {
        foreach ($relations as $relation => $cols) {
            foreach ($cols as $col) {
                $value = \Illuminate\Support\Arr::get($params, $col);
                $query = $query->when(filled($value), fn(Builder $query) => $query->whereRelation($relation, $col, $value));
            }
        }

        return $query;
    }
}

if (!function_exists('search_by_cols')) {
    function search_by_cols(Builder &$query, $value, array $cols = []): Builder
    {
        if (!filled($value)) return $query;

        $query = $query->where(function ($query) use ($value, $cols) {
            foreach ($cols as $col) {
                $query->orWhere($col, 'like', '%' . $value . '%');
            }
        });

        return $query;
    }
}

if (!function_exists('search_relation_by_cols')) {
    function search_relation_by_cols(Builder &$query, $value, array $relations = []): Builder
    {
        if (blank($value) || blank($relations)) return $query;

        foreach ($relations as $relation => $cols) {
            $query = $query->where(function (Builder $query) use ($relation, $value, $cols) {
                foreach ($cols as $col) {
                    $query->orWhereRelation($relation, $col, 'like', '%' . $value . '%');
                }
            });
        }

        return $query;
    }
}

if (!function_exists('paginate_with_params')) {
    function paginate_with_params($query, array $params = []): LengthAwarePaginator
    {
        $perPage = config('app.pagination');

        if (!empty($params['perPage'])) {
            $perPage = (int)$params['perPage'];
        }

        return $query->paginate($perPage);
    }
}

