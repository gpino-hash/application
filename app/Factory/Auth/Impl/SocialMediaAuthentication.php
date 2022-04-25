<?php

namespace App\Factory\Auth\Impl;

use App\Factory\Auth\GuardName;
use App\Factory\Auth\IAuthenticate;
use App\Factory\Auth\ILogin;
use App\Models\User;
use App\UseCase\AuthenticatorResponse;
use App\UseCase\Status;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\ArrayShape;
use Laravel\Socialite\Facades\Socialite;

class SocialMediaAuthentication implements ILogin
{
    use AuthenticatorResponse;

    /**
     * @inheritDoc
     * @throws \Throwable
     */
    #[ArrayShape(["access_token" => "mixed", "token_type" => "string", "user" => "\App\Http\Resources\UserResource"])]
    public function login(array|string $data, string $guardName = GuardName::WEB): array
    {
        $socialMedia = Socialite::driver($data)->stateless()->user();
        $user = User::getUserByEmail($socialMedia->getEmail(), Status::ACTIVE);
        if (empty($user)) {
            $user = resolve(IAuthenticate::class)->register([
                "name" => $socialMedia->getNickname(),
                "email" => $socialMedia->getEmail(),
                "status" => Status::LOCKED,
            ]);
        }

        Auth::guard($guardName)->loginUsingId($user->id);
        return $this->responseLogin($guardName);
    }
}
