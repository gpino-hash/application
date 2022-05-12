<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Status extends Enum
{
    const ACTIVE = "active";
    const INACTIVE = "inactive";
    const LOCKED = "locked";
    const SLOW = "slow";
}