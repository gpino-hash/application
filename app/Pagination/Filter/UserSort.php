<?php

namespace App\Pagination\Filter;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class UserSort
 * @package App\Pagination\Filter
 */
class UserSort extends Filter
{
    private array $columns = [
        "name",
        "email",
        "status",
        "email_verified_at",
        "tags",
        "created_at"
    ];

    /**
     * @inheritDoc
     */
    protected function build(Builder $builder): void
    {
        $this->sort($builder, $this->columns, request()->input("sort_by"), request()->input("sort_dir", "ASC"));
    }
}