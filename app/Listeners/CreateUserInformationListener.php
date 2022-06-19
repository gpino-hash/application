<?php

namespace App\Listeners;

use App\Events\CreateUserInformation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Arr;

class CreateUserInformationListener implements ShouldQueue
{

    private array $relations = [
        "avatar",
        "phone",
        "address",
    ];

    /**
     * Handle the event.
     *
     * @param CreateUserInformation $event
     * @return void
     */
    public function handle(CreateUserInformation $event)
    {
        $type = Arr::has($event->data, "type") ? Arr::get($event->data, "type") : "guest";
        if ($event->isAdmin && Arr::has($event->data, "abilities")) {
            $event->user->createToken($type, Arr::only($event->data, "abilities"));
        }

        $userInfo = $event->user
            ->userInformation()
            ->create(Arr::only($event->data, ["firstname", "lastname", "birthdate", "id_number"]));

        foreach ($this->relations as $relation) {
            collect($event->data[$relation])->each(function () use ($userInfo, $relation, $event) {
                if (Arr::has($event->data, $relation)) {
                    $userInfo->{$relation}()->createMany($event->data[$relation]);
                }
            });
        }
    }
}
