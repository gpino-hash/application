<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserObserve
{
    /**
     * Handle the User "creating" event.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function creating(User $user)
    {
        $this->updatePassword($user);
    }

    /**
     * Handle the User "updating" event.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function updating(User $user)
    {
        $this->updatePassword($user);
    }

    /**
     * Handle the User "deleting" event.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function deleting(User $user)
    {
        $userInformation = $user->userInformation()->firstOrFail();
        $userInformation->avatar()->delete();
        $userInformation->address()->delete();
        $userInformation->phone()->delete();
        $userInformation->delete();
    }

    /**
     * @param User $user
     * @return void
     */
    private function updatePassword(User $user): void
    {
        $user->password = Hash::make($user->password);
    }
}
