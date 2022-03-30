<?php

namespace App\Http\Factory\Auth\Impl;

use App\Http\Factory\Auth\Authenticate;
use App\Http\Factory\Auth\AuthenticateTrait;
use App\Http\Factory\Auth\GuardName;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\ArrayShape;

class ApiAuthentication implements Authenticate
{
    use AuthenticateTrait;

    private array $credentials;
    private bool $remember;
    private GuardName $guard;

    public function __construct(GuardName $guard, array $credentials, bool $remember)
    {
        $this->credentials = $credentials;
        $this->remember = $remember;
        $this->guard = $guard;
    }

    /**
     * @return array
     * @throws \Throwable
     */
    #[ArrayShape(["access_token" => "mixed", "token_type" => "string", "user" => "\App\Http\Resources\UserResource"])]
    public function handle(): array
    {
        throw_unless(Auth::guard($this->guard->name)->attempt($this->credentials, $this->remember), AuthenticationException::class);
        return $this->response();
    }
}
