<?php

namespace App\Providers;

use App\Factory\Auth\AbstractAuthFactory;
use App\Factory\Auth\IApi;
use App\Factory\Auth\Impl\Api;
use App\Factory\Auth\Impl\DefaultAbstractAuthFactory;
use App\Factory\Auth\Impl\SocialNetwork;
use App\Factory\Auth\ISocialNetwork;
use Illuminate\Support\ServiceProvider;

class UseCaseProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(ISocialNetwork::class, SocialNetwork::class);
        $this->app->bind(IApi::class, Api::class);
        $this->app->bind(AbstractAuthFactory::class, DefaultAbstractAuthFactory::class);
    }
}
