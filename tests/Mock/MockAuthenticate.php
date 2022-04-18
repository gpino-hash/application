<?php

namespace Tests\Mock;

use App\Http\Builder\Auth\UserData;
use App\UseCase\Status;

trait MockAuthenticate
{
    /**
     * @param array $credentials
     * @return UserData
     */
    public function mockLoginData(array $credentials): UserData
    {
        $builder = new UserData();
        $builder->name = $credentials["username"];
        $builder->password = $credentials["password"];
        $builder->status = Status::fromValue($credentials["status"])->value;
        return $builder->build();
    }
}
