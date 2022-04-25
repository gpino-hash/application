<?php


namespace App\Factory\Auth;

interface ILogin
{
    /**
     * @param array|string $data
     * @param string $guardName
     * @return array
     */
    public function login(array|string $data, string $guardName = GuardName::WEB): array;

}