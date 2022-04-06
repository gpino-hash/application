<?php

namespace App\Factory\Auth\Impl;

use App\Factory\Auth\AuthenticatorResponse;
use App\Factory\Auth\GuardName;
use App\Factory\Auth\IApi;
use App\Http\Data\Auth\UserData;
use App\Models\User;
use App\Notifications\ActiveUserNotification;
use App\Repository\User\ICreateUser;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\ArrayShape;
use function resolve;
use function throw_unless;

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
    public function register(GuardName $guardName, UserData $userData): User
    {
        $rememberToken = self::generate();
        $user = resolve(ICreateUser::class)->create([
            "email" => $userData->getEmail(),
            "name" => $userData->getUsername(),
            "status" => $userData->getStatus()->getName(),
            "remember_token" => $rememberToken,
        ]);
        $user->notify(new ActiveUserNotification($rememberToken, $userData->getName() ?: $userData->getUsername()));
        return $user;
    }
}
