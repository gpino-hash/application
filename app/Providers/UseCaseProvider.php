<?php

namespace App\Providers;

use App\Factory\Auth\IAbstractAuthFactory;
use App\Factory\Auth\IAuthenticate;
use App\Factory\Auth\ILogin;
use App\Factory\Auth\Impl\AbstractAuthFactory;
use App\Factory\Auth\Impl\ApiAuthentication;
use App\Factory\Auth\Impl\SocialMediaAuthentication;
use Illuminate\Support\ServiceProvider;

class UseCaseProvider extends ServiceProvider
{

    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public array $bindings = [
        ILogin::class => SocialMediaAuthentication::class,
        IAuthenticate::class => ApiAuthentication::class,
        IAbstractAuthFactory::class => AbstractAuthFactory::class,
    ];
}
