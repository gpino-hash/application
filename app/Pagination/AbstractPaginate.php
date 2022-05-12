<?php

namespace App\Pagination;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

abstract class AbstractPaginate
{
    /**
     * @return mixed
     */
    public function paginate(): mixed
    {
        return resolve(Pipeline::class)
            ->send($this->getBuilder())
            ->through([...$this->filter(), ...$this->sort()])
            ->thenReturn()
            ->paginate(request()->input("per_page"),
                $this->showColumns(),
                $this->getPageName(),
                request()->input("page"));
    }

    /**
     * @return array
     */
    protected abstract function filter(): array;

    /**
     * @return array
     */
    protected abstract function sort(): array;

    /**
     * @return Builder
     */
    protected abstract function getBuilder(): Builder;

    /**
     * @return array
     */
    protected abstract function showColumns(): array;

    /**
     * @return string
     */
    protected abstract function getPageName(): string;
}