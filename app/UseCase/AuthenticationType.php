<?php


namespace App\UseCase;


use BenSampo\Enum\Enum;

final class AuthenticationType extends Enum
{
    const API = "api";
    const SOCIAL_NETWORK = "social-network";
}