<?php

namespace App\Providers;

use App\Http\Repository\User\ICreateUser;
use App\Http\Repository\User\Impl\UserRepository;
use App\Http\Repository\User\IUserByEmail;
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
        $this->app->bind(IUserByEmail::class, UserRepository::class);
        $this->app->bind(ICreateUser::class, UserRepository::class);
    }
}
