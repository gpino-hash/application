<?php

namespace App\Providers;

use App\Events\CreateUserInformation;
use App\Events\Registered;
use App\Listeners\CreateUserInformationListener;
use App\Listeners\SendRegistrationConfirmationEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendRegistrationConfirmationEmail::class
        ],
        CreateUserInformation::class => [
            CreateUserInformationListener::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
