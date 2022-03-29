<?php

namespace App\Http\Factory\Auth\Impl;

use App\Http\Factory\Auth\Authenticate;
use App\Http\Factory\Auth\GuardName;
use App\Http\Repository\User\GetUserByEmailGiver;
use App\Http\Resources\UserResource;
use App\Http\UseCase\TypeSocialNetworks;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use Laravel\Socialite\Facades\Socialite;

class SocialNetworkAuthentication implements Authenticate
{
    private GuardName $guardName;
    private TypeSocialNetworks $typeSocialNetworks;

    /**
     * @param GuardName $guardName
     * @param TypeSocialNetworks $typeSocialNetworks
     */
    public function __construct(GuardName $guardName, TypeSocialNetworks $typeSocialNetworks)
    {
        $this->guardName = $guardName;
        $this->typeSocialNetworks = $typeSocialNetworks;
    }

    /**
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Throwable
     */
    #[ArrayShape(["access_token" => "mixed", "token_type" => "string", "user" => "\App\Http\Resources\UserResource"])]
    public function handle(): array
    {
        $socialMedia = Socialite::driver(Str::lower($this->typeSocialNetworks->name))->stateless()->user();
        $user = resolve(GetUserByEmailGiver::class)->getUserByEmail($socialMedia->getEmail());
        throw_if(empty($user), AuthenticationException::class);
        Auth::guard($this->guardName->name)->login($user);
        return [
            "access_token" => $user->createToken(Authenticate::TOKEN_NAME)->plainTextToken,
            "token_type" => "Bearer",
            "user" => new UserResource($user),
        ];
    }
}
