<?php

namespace App\Http\Factory\Auth;

use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\ArrayShape;

trait AuthenticateTrait
{
    /**
     * @return string
     */
    public function getToken(): string
    {
        return Auth::user()->createToken(Authenticate::TOKEN_NAME)->plainTextToken;
    }

    /**
     * @return array
     */
    #[ArrayShape(["access_token" => "string", "token_type" => "string", "user" => "\App\Http\Resources\UserResource"])]
    private function response(): array
    {
        return [
            "access_token" => $this->getToken(),
            "token_type" => "Bearer",
            "user" => new UserResource(Auth::user()),
        ];
    }
}
