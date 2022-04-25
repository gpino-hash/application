<?php


namespace App\Factory\Auth\Impl;

use App\Factory\Auth\IAbstractAuthFactory;
use App\Factory\Auth\IAuthenticate;
use App\Factory\Auth\ILogin;
use App\UseCase\AuthenticationType;
use JetBrains\PhpStorm\ArrayShape;

class AbstractAuthFactory implements IAbstractAuthFactory
{

    /**
     * @inheritDoc
     * @throws \Throwable
     */
    #[ArrayShape(["access_token" => "mixed", "token_type" => "string", "user" => "\App\Http\Resources\UserResource"])]
    public function build(string $type = AuthenticationType::API): mixed
    {
        return match ($type) {
            "api" => resolve(IAuthenticate::class),
            "social-network" => resolve(ILogin::class),
        };
    }
}