<?php

namespace App\Repository\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface IUserByEmail
{
    /**
     * @param string $email
     * @param string $status
     * @return Model|Builder|null
     */
    public function getUserByEmail(string $email, string $status): Model|Builder|null;
}
