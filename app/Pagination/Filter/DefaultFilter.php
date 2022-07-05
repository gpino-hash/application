<?php

namespace App\Pagination\Filter;

use Illuminate\Database\Eloquent\Builder;

class DefaultFilter extends Filter
{
    private array $columns = [
        "search",
        "from",
        "to",
    ];

    /**
     * @inheritDoc
     */
    protected function build(Builder $builder): void
    {
        $this->filter($builder, $this->columns);
    }
}