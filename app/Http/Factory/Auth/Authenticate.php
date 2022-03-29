<?php

namespace App\Http\Factory\Auth;

interface Authenticate
{
    public const TOKEN_NAME = "application";

    /**
     * @return array
     */
    public function handle(): array;

}
