<?php

namespace App\Factory\Auth;

use App\Models\User;

interface IAuthenticate extends ILogin
{
    /**
     * @param array $data
     * @return User
     */
    public function register(array $data): User;

    /**
     * @param string $email
     * @return array
     */
    public function forgot(string $email): array;

    /**
     * @param array $data
     * @return array
     */
    public function reset(array $data): array;
}