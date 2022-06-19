<?php

namespace App\Listeners;

use App\Events\UpdateUserInformation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Arr;

class UpdateUserInformationListener implements ShouldQueue
{

    private array $relations = [
        "avatar",
        "phone",
        "address",
    ];

    /**
     * Handle the event.
     *
     * @param UpdateUserInformation $event
     * @return void
     */
    public function handle(UpdateUserInformation $event)
    {
        $userInformation = $event->user->userInformation();
        $userInformation->update(Arr::only($event->data, ["firstname", "lastname", "birthdate", "id_number"]));
        $userInformation = $userInformation->first();
        foreach ($this->relations as $relation) {
            collect(request()->input($relation))->each(function ($information) use ($userInformation, $relation) {
                $userInformation->{$relation}()->where("uuid", $information["uuid"])->update($information);
            });
        }
    }
}
