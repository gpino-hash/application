<?php

namespace App\Factory\Auth;

use App\Http\Builder\Auth\ResetPasswordData;
use App\Http\Builder\Auth\UserData;
use App\Models\User;

interface IApi
{
    /**
     * @param string $guardName
     * @param UserData $userData
     * @param bool $remember
     * @return array
     */
    public function login(string $guardName, UserData $userData, bool $remember): array;

    /**
     * @param UserData $userData
     * @param $exclude
     * @return User
     */
    public function register(UserData $userData, ...$exclude): User;

    /**
     * @param string $email
     * @return array
     */
    public function forgot(string $email): array;

    /**
     * @param ResetPasswordData $passwordData
     * @return array
     */
    public function reset(ResetPasswordData $passwordData): array;
}
