<?php

namespace App\Enums;

use App\Enums\Traits\HasValues;

enum Status: string
{
    use HasValues;

    case ACTIVE = "active";
    case INACTIVE = "inactive";
    case LOCKED = "locked";
    case SLOW = "slow";
}
