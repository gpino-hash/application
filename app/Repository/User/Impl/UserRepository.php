<?php

namespace App\Repository\User\Impl;

use App\Models\User;
use App\Repository\User\IUser;
use App\Repository\User\Repository;
use App\UseCase\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class UserRepository extends Repository implements IUser
{
    protected string $model = User::class;

    /**
     * @param string $email
     * @param Status $status
     * @return Model|Builder|null
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getUserByEmail(string $email, Status $status): Model|Builder|null
    {
        return $this->getModelBuilder()->where("email", $email)
            ->where("status", $status->getName())
            ->first();
    }
}