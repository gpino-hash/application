<?php


namespace App\Http\Traits;


use App\UseCase\AuthenticationType;

trait TryAccess
{
    /**
     * @param string $type
     * @return mixed
     */
    private function tryAccessAuth(string $type = AuthenticationType::API): mixed
    {
        return $this->authFactory->build($type);
    }
}