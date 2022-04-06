<?php

namespace App\UseCase;

use Illuminate\Support\Str;

enum TypeSocialNetworks
{
    case GOOGLE;
    case FACEBOOK;
    case TWITTER;

    /**
     * @param $type
     * @return TypeSocialNetworks
     */
    public static function getTypeSocialNetworks($type): TypeSocialNetworks
    {
        foreach (self::cases() as $case) {
            if ($case->name == Str::upper($type)) {
                return $case;
            }
        }

        return self::GOOGLE;
    }
}
