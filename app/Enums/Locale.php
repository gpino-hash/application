<?php

namespace App\Enums;

use App\Enums\Traits\HasValues;

enum Locale: string
{
    use HasValues;

    case SPANISH = "es";
    case ENGLISH = "en";
    case FRENCH = "fr";
    case GERMAN = "de";
    case ITALIAN = "it";
    case JAPANESE = "ja";
    case KOREAN = "ko";
    case CHINESE = "zh";
}