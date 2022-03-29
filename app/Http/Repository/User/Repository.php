<?php

namespace App\Http\Repository\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class Repository
{
    protected string $model = Model::class;

    /**
     * @return Builder
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getModelBuilder(): Builder
    {
        return app()->get($this->model)->query();
    }
}
