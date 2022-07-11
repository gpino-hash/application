<?php

namespace App\Models\Builder;

use Illuminate\Database\Eloquent\Builder;

class ProductBuilder extends Builder
{
    /**
     * @return static
     */
    public function getPaginatorQuery(): static
    {
        return $this->with([
            "site.country.currencies",
            "picture",
            "currency",
        ]);
    }
}