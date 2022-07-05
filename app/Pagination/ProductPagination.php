<?php

namespace App\Pagination;

use App\Models\Product;
use App\Pagination\Filter\DefaultFilter;
use App\Pagination\Filter\StatusFilter;
use Illuminate\Database\Eloquent\Builder;

class ProductPagination extends Creator
{

    /**
     * @inheritDoc
     */
    public function factory(): Builder
    {
        return Product::query()->getPaginatorQuery();
    }

    /**
     * @inheritDoc
     */
    protected function showColumns(): array
    {
        return [
            "uuid",
            "title",
            "description",
            "stock",
            "price",
            "status",
            "site_uuid",
            "created_at",
        ];
    }

    /**
     * @inheritDoc
     */
    protected function filteredOut(): array
    {
        return [
            DefaultFilter::class,
            StatusFilter::class,
        ];
    }
}