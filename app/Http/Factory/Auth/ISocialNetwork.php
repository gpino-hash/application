<?php

namespace App\Http\Factory\Auth;

use App\Http\UseCase\TypeSocialNetworks;

interface ISocialNetwork
{
    /**
     * @param GuardName $guardName
     * @param TypeSocialNetworks $typeSocialNetworks
     * @return array
     */
    public function login(GuardName $guardName, TypeSocialNetworks $typeSocialNetworks): array;

    /**
     * @param GuardName $guardName
     * @param TypeSocialNetworks $typeSocialNetworks
     * @return string
     */
    public function register(GuardName $guardName, TypeSocialNetworks $typeSocialNetworks): string;
}
