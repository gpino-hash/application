<?php

namespace App\Pagination;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

abstract class Creator
{

    /**
     * @var string
     */
    protected string $pageName = "page";

    /**
     * @return mixed
     */
    public function paginate(): mixed
    {
        return resolve(Pipeline::class)
            ->send($this->factory())
            ->through($this->filteredOut())
            ->thenReturn()
            ->paginate(request()->input("per_page"),
                $this->showColumns(),
                $this->pageName,
                request()->input("page"));
    }

    /**
     * @return Builder
     */
    public abstract function factory(): Builder;

    /**
     * @return array
     */
    protected abstract function showColumns(): array;

    /**
     * @return array
     */
    protected abstract function filteredOut(): array;
}