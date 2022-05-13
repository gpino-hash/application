<?php


namespace App\Pagination;


use App\Models\User;
use App\Pagination\Filter\UserFilter;
use App\Pagination\Filter\UserSort;
use Illuminate\Database\Eloquent\Builder;
use JetBrains\PhpStorm\Pure;

class UserPagination extends AbstractPaginate
{

    /**
     * @var array|string[]
     */
    private array $showColumns = [
        "id",
        "name",
        "email",
        "status",
        "email_verified_at",
        "tags",
        "created_at"
    ];

    /**
     * @var array|string[]
     */
    private array $filters = [
        UserFilter::class,
        UserSort::class,
    ];

    /**
     * UserPagination constructor.
     */
    #[Pure]
    public function __construct()
    {
        parent::__construct($this->filters, $this->showColumns, "users");
    }


    /**
     * @inheritDoc
     */
    protected function getBuilder(): Builder
    {
        return User::query();
    }
}