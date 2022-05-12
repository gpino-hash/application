<?php


namespace App\Pagination\Filter;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

abstract class Filter
{

    protected abstract function getColumns(): array;

    /**
     * @param Builder $builder
     * @param $next
     * @return mixed
     */
    public function handle(Builder $builder, $next): mixed
    {
        $data = request()->only($this->getColumns());
        foreach ($data as $key => $value) {
            if (in_array($key, $this->getColumns())) {
                $builder->{Str::camel($key)}($value);
            }
        }
        return $next($builder);
    }
}