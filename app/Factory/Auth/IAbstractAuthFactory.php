<?php


namespace App\Factory\Auth;


use App\UseCase\AuthenticationType;


interface IAbstractAuthFactory
{
    /**
     * @param string $type
     * @return mixed
     */
    public function build(string $type = AuthenticationType::API): mixed;
}