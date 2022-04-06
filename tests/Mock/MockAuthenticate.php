<?php

namespace Tests\Mock;

use App\Http\Builder\Auth\UserBuilder;
use App\Http\Data\Auth\UserData;
use App\UseCase\Status;

trait MockAuthenticate
{
    /**
     * @param array $credentials
     * @return UserData
     */
    private function mockLoginData(array $credentials): UserData
    {
        return UserBuilder::builder()
            ->username($credentials["username"])
            ->password($credentials["password"])
            ->status(Status::getUserStatus($credentials["status"]))
            ->build();
    }
}
