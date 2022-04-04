<?php

namespace App\Providers;

use App\Http\Factory\Auth\IApi;
use App\Http\Factory\Auth\Impl\Api;
use App\Http\Factory\Auth\Impl\SocialNetwork;
use App\Http\Factory\Auth\ISocialNetwork;
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
    }
}
