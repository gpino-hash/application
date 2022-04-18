<?php

namespace App\Repository\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function app;

abstract class Repository
{
    protected string $model = Model::class;

    /**
     * @param array $data
     */
    abstract protected function toProcessData(array &$data): void;

    /**
     * @inheritDoc
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function create(array $data): Model|Builder
    {
        $errorMessage = "Error trying to create user.";
        DB::beginTransaction();
        try {
            $this->toProcessData($data);
            $create = $this->getModelBuilder()->create($data);
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::stack(["stack"])->error($exception->getMessage(), [$exception]);
            throw new ModelNotFoundException($errorMessage);
        }

        return $create;
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
