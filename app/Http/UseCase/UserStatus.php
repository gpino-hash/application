<?php

namespace App\Http\UseCase;

use Illuminate\Support\Str;

enum UserStatus
{
    case ACTIVE;
    case INACTIVE;
    case LOCKED;
    case SLOW;

    /**
     * @param $type
     * @return UserStatus
     */
    public static function getUserStatus($type): UserStatus
    {
        foreach (self::cases() as $case) {
            if ($case->name == Str::upper($type)) {
                return $case;
            }
        }

        return self::ACTIVE;
    }
}
