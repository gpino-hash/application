<?php

namespace App\Pagination;

use App\Models\User;
use App\Pagination\Filter\DefaultFilter;
use App\Pagination\Filter\StatusFilter;
use App\Pagination\Filter\UserEmailNotVerifiedQueryFilter;
use App\Pagination\Filter\UserSort;
use Illuminate\Database\Eloquent\Builder;

class UserPagination extends Creator
{

    /**
     * @inheritDoc
     */
    public function factory(): Builder
    {
        return User::query()->getPaginatorQuery();
    }

    /**
     * @inheritDoc
     */
    protected function showColumns(): array
    {
        return [
            "uuid",
            "name",
            "email",
            "status",
            "email_verified_at",
            "tags",
            "created_at"
        ];
    }

    /**
     * @inheritDoc
     */
    protected function filteredOut(): array
    {
        return [
            UserEmailNotVerifiedQueryFilter::class,
            DefaultFilter::class,
            StatusFilter::class,
            UserSort::class,
        ];
    }
}