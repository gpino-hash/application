<?php

namespace App\Pagination;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

abstract class AbstractPaginate
{

    public function __construct(private array $through, private array $showColumns = ["*"], private string $pageName = "page")
    {
    }

    /**
     * @return mixed
     */
    public function paginate(): mixed
    {
        return resolve(Pipeline::class)
            ->send($this->getBuilder())
            ->through($this->through)
            ->thenReturn()
            ->paginate(request()->input("per_page"),
                $this->showColumns,
                $this->pageName,
                request()->input("page"));
    }

    /**
     * @return Builder
     */
    protected abstract function getBuilder(): Builder;
}