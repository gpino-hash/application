<?php

namespace App\Http\Factory\Auth\Impl;

use App\Http\Factory\Auth\AuthenticatorResponse;
use App\Http\Factory\Auth\GuardName;
use App\Http\Factory\Auth\ISocialNetwork;
use App\Http\Repository\User\ICreateUser;
use App\Http\Repository\User\IUserByEmail;
use App\Http\UseCase\Status;
use App\Http\UseCase\TypeSocialNetworks;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use Laravel\Socialite\Facades\Socialite;

class SocialNetwork implements ISocialNetwork
{
    use AuthenticatorResponse;

    /**
     * @inheritDoc
     * @throws \Throwable
     */
    #[ArrayShape(["access_token" => "mixed", "token_type" => "string", "user" => "\App\Http\Resources\UserResource"])]
    public function login(GuardName $guardName, TypeSocialNetworks $typeSocialNetworks): array
    {
        $socialMedia = Socialite::driver(Str::lower($typeSocialNetworks->name))->stateless()->user();
        $user = resolve(IUserByEmail::class)->getUserByEmail($socialMedia->getEmail(), Status::ACTIVE);
        throw_if(empty($user), AuthenticationException::class);
        Auth::guard($guardName->name)->login($user);
        return $this->response();
    }

    /**
     * @inheritDoc
     */
    public function register(GuardName $guardName, TypeSocialNetworks $typeSocialNetworks): string
    {
        $socialMedia = Socialite::driver(Str::lower($typeSocialNetworks->name))->user();
        $rememberToken = Str::random(50);
        resolve(ICreateUser::class)->create([
            "email" => $socialMedia->getEmail(),
            "name" => $socialMedia->getNickname(),
            "status" => Status::LOCKED->getName(),
            "remember_token" => $rememberToken,
        ]);

        return $rememberToken;
    }
}
