<?php

namespace App\Factory\Auth\Impl;

use App\Factory\Auth\IApi;
use App\Factory\Auth\ISocialNetwork;
use App\Http\Builder\Auth\UserData;
use App\Repository\User\IUserByEmail;
use App\UseCase\AuthenticatorResponse;
use App\UseCase\Status;
use Illuminate\Support\Facades\Auth;
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
    public function handle(string $guardName, string $typeSocialNetworks): array
    {
        $socialMedia = Socialite::driver($typeSocialNetworks)->stateless()->user();
        $user = resolve(IUserByEmail::class)->getUserByEmail($socialMedia->getEmail(), Status::ACTIVE);
        if (empty($user)) {
            $user = resolve(IApi::class)->register($this->getUser($socialMedia), "password", "tags");
        }
        Auth::guard($guardName)->login($user);
        return $this->responseLogin($guardName);
    }

    /**
     * @param SocialMediaUser $socialMedia
     * @return UserData
     */
    private function getUser(SocialMediaUser $socialMedia): UserData
    {
        $builder = new UserData();
        $builder->name = $socialMedia->getNickname();
        $builder->email = $socialMedia->getEmail();
        $builder->status = Status::LOCKED;
        return $builder->build();
    }
}
