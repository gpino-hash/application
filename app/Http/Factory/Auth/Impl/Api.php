<?php

namespace App\Http\Factory\Auth\Impl;

use App\Http\Data\Auth\UserData;
use App\Http\Factory\Auth\AuthenticatorResponse;
use App\Http\Factory\Auth\GuardName;
use App\Http\Factory\Auth\IApi;
use App\Http\Repository\User\ICreateUser;
use App\Http\UseCase\Status;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

class Api implements IApi
{
    use AuthenticatorResponse;

    /**
     * @inheritDoc
     * @throws \Throwable
     */
    #[ArrayShape(["access_token" => "string", "token_type" => "string", "user" => "\App\Http\Resources\UserResource"])]
    public function login(GuardName $guardName, UserData $userData, bool $remember): array
    {
        $key = filter_var($userData->getUsername(), FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        $credentials = [
            $key => $userData->getUsername(),
            "password" => $userData->getPassword(),
            "status" => $userData->getStatus()->getName(),
        ];
        throw_unless(Auth::guard($guardName->name)->attempt($credentials, $remember), AuthenticationException::class);
        return $this->response();
    }

    /**
     * @inheritDoc
     */
    public function register(GuardName $guardName, UserData $userData): string
    {
        $rememberToken = Str::random(50);
        resolve(ICreateUser::class)->create([
            "email" => $userData->getEmail(),
            "name" => $userData->getUsername(),
            "status" => Status::LOCKED->getName(),
            "remember_token" => $rememberToken,
        ]);

        return $rememberToken;
    }
}
