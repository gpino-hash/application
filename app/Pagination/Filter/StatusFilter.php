<?php

namespace App\Pagination\Filter;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Builder;

class StatusFilter extends Filter
{

    /**
     * @inheritDoc
     */
    protected function build(Builder $builder): void
    {
        $builder->status(request()->input("status", Status::ACTIVE));
    }
}