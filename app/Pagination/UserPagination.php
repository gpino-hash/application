<?php


namespace App\Pagination;


use App\Models\User;
use App\Pagination\Filter\Sort;
use App\Pagination\Filter\UserFilter;
use Illuminate\Database\Eloquent\Builder;

class UserPagination extends AbstractPaginate
{

    /**
     * @inheritDoc
     */
    protected function filter(): array
    {
        return [
            UserFilter::class,
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getBuilder(): Builder
    {
        return User::query();
    }

    /**
     * @inheritDoc
     */
    protected function sort(): array
    {
        return [
            Sort::class,
        ];
    }

    /**
     * @inheritDoc
     */
    protected function showColumns(): array
    {
        return [
            "id",
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
    protected function getPageName(): string
    {
        return "users";
    }
}