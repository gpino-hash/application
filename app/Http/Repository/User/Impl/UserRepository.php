<?php

namespace App\Http\Repository\User\Impl;

use App\Http\Repository\User\GetUserGiver;
use App\Http\Repository\User\Repository;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends Repository implements GetUserGiver
{
    protected string $model = User::class;

    /**
     * @param string $email
     * @return Model|Builder|null
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getUserByEmail(string $email): Model|Builder|null
    {
        return $this->getModelBuilder()->where("email", $email)->first();
    }
}
