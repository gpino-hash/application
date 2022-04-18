<?php

namespace App\Factory\Auth;

interface ISocialNetwork
{
    /**
     * @param string $guardName
     * @param string $typeSocialNetworks
     * @return array
     */
    public function handle(string $guardName, string $typeSocialNetworks): array;
}
