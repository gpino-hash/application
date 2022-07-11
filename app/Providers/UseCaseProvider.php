<?php

namespace App\Providers;

use App\UseCase\Auth\IAuthenticate;
use App\UseCase\Auth\Impl\Authenticate;
use App\UseCase\Price\Impl\DefaultPrice;
use App\UseCase\Price\Price;
use Illuminate\Support\ServiceProvider;

class UseCaseProvider extends ServiceProvider
{

    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public array $bindings = [
        IAuthenticate::class => Authenticate::class,
        Price::class => DefaultPrice::class,
    ];
}
