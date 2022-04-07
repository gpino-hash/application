<?php

namespace App\Factory\Auth;

use App\UseCase\TypeSocialNetworks;

interface ISocialNetwork
{
    /**
     * @param GuardName $guardName
     * @param TypeSocialNetworks $typeSocialNetworks
     * @return array
     */
    public function handle(GuardName $guardName, TypeSocialNetworks $typeSocialNetworks): array;
}
