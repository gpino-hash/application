<?php


namespace App\Pagination\Filter;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

abstract class Filter
{

    public abstract function handle(Builder $builder, $next): mixed;

    /**
     * @param Builder $builder
     * @param array $columns
     * @return mixed
     */
    protected function filter(Builder $builder, array $columns): void
    {
        $data = request()->only($columns);
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if (in_array($key, $columns)) {
                    $builder->{Str::camel($key)}($value);
                }
            }
        }

    }

    /**
     * @param Builder $builder
     * @param array $columns
     * @param $sortBy
     * @param $sortDir
     * @return mixed
     */
    protected function sort(Builder $builder, array $columns, mixed $sortBy, mixed $sortDir): void
    {

        if (is_null($sortBy)) {
            $builder->sortCreatedAt();
        } elseif (in_array($sortBy, $columns)) {
            $builder->genericSortBy($sortBy, $sortDir);
        }
    }
}