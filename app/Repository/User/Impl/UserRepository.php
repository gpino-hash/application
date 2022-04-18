<?php

namespace App\Repository\User\Impl;

use App\Models\User;
use App\Repository\User\IUser;
use App\Repository\User\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class UserRepository extends Repository implements IUser
{
    protected string $model = User::class;

    /**
     * @inheritDoc
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getUserByEmail(string $email, string $status): Model|Builder|null
    {
        return $this->getModelBuilder()->where("email", $email)
            ->where("status", $status)
            ->first();
    }

    /**
     * @inheritDoc
     */
    protected function toProcessData(array &$data): void
    {
        $data["password"] = bcrypt($data["password"]);
    }
}
