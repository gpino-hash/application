<?php

namespace App\Pagination\Filter;

use Illuminate\Database\Eloquent\Builder;

class UserSort extends Filter
{
    private array $columns = [
        "id",
        "name",
        "email",
        "status",
        "email_verified_at",
        "tags",
        "created_at"
    ];

    /**
     * @param Builder $builder
     * @param $next
     * @return mixed
     */
    public function handle(Builder $builder, $next): mixed
    {
        $this->sort($builder, $this->columns);
        return $next($builder);
    }
}