<?php

namespace App\UseCase;

use BenSampo\Enum\Enum;

final class SocialNetworkType extends Enum
{
    const GOOGLE = "google";
    const FACEBOOK = "facebook";
    const TWITTER = "twitter";
}
