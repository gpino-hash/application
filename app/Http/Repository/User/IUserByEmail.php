<?php

namespace App\Http\Repository\User;

use App\Http\UseCase\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface IUserByEmail
{
    /**
     * @param string $email
     * @param Status $status
     * @return Model|Builder|null
     */
    public function getUserByEmail(string $email, Status $status): Model|Builder|null;
}
