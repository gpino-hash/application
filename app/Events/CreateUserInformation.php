<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateUserInformation
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param bool $isAdmin
     * @param array $data
     */
    public function __construct(public User $user, public bool $isAdmin, public array $data)
    {
    }
}
