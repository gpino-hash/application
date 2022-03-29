<?php

namespace App\Http\Repository\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface GetUserByEmailGiver
{
    public function getUserByEmail(string $email): Model|Builder|null;
}
