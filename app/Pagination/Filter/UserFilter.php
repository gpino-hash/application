<?php

namespace App\Pagination\Filter;

use Illuminate\Database\Eloquent\Builder;

class UserFilter extends Filter
{
    private array $columns = [
        "name",
        "email",
        "from",
        "to",
    ];

    public function handle(Builder $builder, $next): mixed
    {
        $this->filter($builder, $this->columns);
        return $next($builder);
    }
}