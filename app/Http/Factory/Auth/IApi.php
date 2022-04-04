<?php

namespace App\Http\Factory\Auth;

use App\Http\Data\Auth\UserData;

interface IApi
{
    /**
     * @param GuardName $guardName
     * @param UserData $userData
     * @param bool $remember
     * @return array
     */
    public function login(GuardName $guardName, UserData $userData, bool $remember): array;

    /**
     * @param GuardName $guardName
     * @param UserData $userData
     * @return string
     */
    public function register(GuardName $guardName, UserData $userData): string;
}
