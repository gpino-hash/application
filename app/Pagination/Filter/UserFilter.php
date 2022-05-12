<?php

namespace App\Pagination\Filter;

class UserFilter extends Filter
{
    /**
     * @return string[]
     */
    protected function getColumns(): array
    {
        return [
            "name",
            "email",
            "from",
            "to",
        ];
    }
}