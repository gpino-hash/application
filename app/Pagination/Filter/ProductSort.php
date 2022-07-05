<?php

namespace App\Pagination\Filter;

use Illuminate\Database\Eloquent\Builder;

class ProductSort extends Filter
{
    private array $columns = [
        "title",
        "status",
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