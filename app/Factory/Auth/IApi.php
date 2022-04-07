<?php

namespace App\Factory\Auth;

use App\Http\Data\Auth\UserData;
use App\Models\User;

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
     * @return User
     */
    public function register(GuardName $guardName, UserData $userData): User;
}
