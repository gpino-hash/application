<?php

namespace App\Factory\Auth\Impl;

use App\Factory\Auth\AuthenticatorResponse;
use App\Factory\Auth\GuardName;
use App\Factory\Auth\IApi;
use App\Factory\Auth\ISocialNetwork;
use App\Http\Builder\Auth\UserBuilder;
use App\Http\Data\Auth\UserData;
use App\Repository\User\IUserByEmail;
use App\UseCase\Status;
use App\UseCase\TypeSocialNetworks;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use Laravel\Socialite\Contracts\User as SocialMediaUser;
use Laravel\Socialite\Facades\Socialite;
use function resolve;

class SocialNetwork implements ISocialNetwork
{
    use AuthenticatorResponse;

    /**
     * @inheritDoc
     * @throws \Throwable
     */
    #[ArrayShape(["access_token" => "mixed", "token_type" => "string", "user" => "\App\Http\Resources\UserResource"])]
    public function handle(GuardName $guardName, TypeSocialNetworks $typeSocialNetworks): array
    {
        $socialMedia = Socialite::driver(Str::lower($typeSocialNetworks->name))->stateless()->user();
        $user = resolve(IUserByEmail::class)->getUserByEmail($socialMedia->getEmail(), Status::ACTIVE);
        if (empty($user)) {
            $user = resolve(IApi::class)->register($guardName, $this->getUser($socialMedia));
        }
        Auth::guard($guardName->name)->login($user);
        return $this->response();
    }

    /**
     * @param SocialMediaUser $socialMedia
     * @return UserData
     */
    private function getUser(SocialMediaUser $socialMedia): UserData
    {
        return UserBuilder::builder()
            ->name($socialMedia->getNickname())
            ->email($socialMedia->getEmail())
            ->status(Status::LOCKED)
            ->build();
    }
}
