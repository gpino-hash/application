<?php

namespace App\Pagination\Filter;

use Illuminate\Database\Eloquent\Builder;

class Sort
{
    /**
     * @param Builder $builder
     * @param $next
     * @return mixed
     */
    public function handle(Builder $builder, $next): mixed
    {
        $column = request()->input("sort_by", "created_at");
        $dir = request()->input("sort_dir", "ASC");

        return $next($builder)
            ->reorder()
            ->orderBy($column, $dir);
    }
}