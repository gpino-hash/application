<?php

namespace App\Providers;

use App\Repository\User\Impl\UserRepository;
use App\Repository\User\IPassword;
use App\Repository\User\IUser;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
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
        $this->app->bind(IUser::class, UserRepository::class);
        $this->app->bind(IPassword::class, UserRepository::class);
    }
}
