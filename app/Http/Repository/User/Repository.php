<?php

namespace App\Http\Repository\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

abstract class Repository
{
    protected string $model = Model::class;

    /**
     * @param array $data
     * @return Model|Builder
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function create(array $data): Model|Builder
    {
        $errorMessage = "Error trying to create user.";
        DB::beginTransaction();
        try {
            $user = $this->getModelBuilder()->create($data);
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw new ModelNotFoundException($errorMessage);
        }

        return $user;
    }

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
