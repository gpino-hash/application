<?php


namespace App\UseCase\Auth;


use App\DataTransferObjects\UserData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface IAuthenticate
{
    /**
     * @param UserData|string $data
     * @param string $guardName
     * @return array
     */
    public function login(UserData|string $data, string $guardName = "web"): array;

    /**
     * @param UserData $data
     * @return Model|Builder
     */
    public function register(UserData $data): Model|Builder;

    /**
     * @param string $email
     * @return array
     */
    public function forgot(string $email): array;

    /**
     * @param UserData $data
     * @return array
     */
    public function reset(UserData $data): array;
}