<?php

namespace App\Enums;

use App\Enums\Traits\HasValues;

enum UserType: string
{
    use HasValues;

    case ADMIN = "admin";
    case MODERATOR = "moderator";
    case USER = "user";

    public function isAdmin(): bool
    {
        return match ($this) {
            self::ADMIN => true,
            default => false
        };
    }
}