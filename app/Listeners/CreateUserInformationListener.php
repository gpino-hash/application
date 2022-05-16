<?php

namespace App\Listeners;

use App\Events\CreateUserInformation;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateUserInformationListener implements ShouldQueue
{

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'listeners';

    /**
     * Handle the event.
     *
     * @param \App\Events\CreateUserInformation $event
     * @return void
     */
    public function handle(CreateUserInformation $event)
    {
        $userInformation = $event->user->userInformation()
            ->create(request()->only("firstname", "lastname", "birthdate", "id_number"));
        $userInformation->avatar()->createMany(request()->input("avatar", []));
        $userInformation->phone()->createMany(request()->input("phone", []));
        $userInformation->address()->createMany(request()->input("address", []));
    }
}
