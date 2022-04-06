<?php

namespace App\Repository\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface ICreateUser
{
    /**
     * @param array $data
     * @return Model|Builder
     */
    public function create(array $data): Model|Builder;
}
