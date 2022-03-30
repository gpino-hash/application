<?php

namespace App\Providers;

use App\Http\Repository\User\IGetUserByEmail;
use App\Http\Repository\User\Impl\UserRepository;
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
        $this->app->bind(IGetUserByEmail::class, UserRepository::class);
    }
}
