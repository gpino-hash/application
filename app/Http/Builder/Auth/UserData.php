<?php

namespace App\Http\Builder\Auth;

use App\Http\Builder\Builder;

class UserData extends Builder
{

    protected $attributes = [
        "email",
        "password",
        "status",
        "tags",
        "name",
    ];
}
