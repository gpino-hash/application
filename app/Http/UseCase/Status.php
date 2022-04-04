<?php

namespace App\Http\UseCase;

use Illuminate\Support\Str;

enum Status
{
    case ACTIVE;
    case INACTIVE;
    case LOCKED;
    case SLOW;

    /**
     * @param $type
     * @return Status
     */
    public static function getUserStatus($type): Status
    {
        foreach (self::cases() as $case) {
            if ($case->name == Str::upper($type)) {
                return $case;
            }
        }

        return self::ACTIVE;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return Str::lower($this->name);
    }
}
