<?php

namespace App\Listeners;

use App\Events\CreateUserInformation;
use App\Events\Registered;
use App\Notifications\ActiveUserNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendRegistrationConfirmationEmail implements ShouldQueue
{

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'confirmationEmailListeners';


    /**
     * Handle the event.
     *
     * @param CreateUserInformation $event
     * @return void
     */
    public function handle(Registered|CreateUserInformation $event)
    {
        if (!$event->user->hasVerifiedEmail()) {
            $event->user->notify(new ActiveUserNotification($event->user));
        }
    }
}
