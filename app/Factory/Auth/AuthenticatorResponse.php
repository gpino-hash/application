<?php

namespace App\Factory\Auth;

use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

trait AuthenticatorResponse
{
    private string $token = "application";

    static mixed $testToken = null;

    /**
     * @return mixed|string
     */
    public static function generate(): mixed
    {
        return static::$testToken ?: Str::random(60);
    }

    /**
     * @param $string
     * @return void
     */
    public static function setTest($string)
    {
        static::$testToken = $string;
    }

    /**
     * @return string
     */
    protected function getToken(): string
    {
        return Auth::user()->createToken($this->token)->plainTextToken;
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
