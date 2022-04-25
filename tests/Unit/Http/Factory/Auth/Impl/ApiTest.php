<?php

namespace Tests\Unit\Http\Factory\Auth\Impl;

use App\Factory\Auth\Impl\ApiAuthentication;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

it("Check that it throws an exception when the registered user with the email is not found.", function () {
    $credentials = [
        "username" => "test@test.com",
        "password" => "test",
        "status" => "active",
        "remember" => false,
    ];
    Auth::shouldReceive('guard')
        ->andReturnSelf()
        ->shouldReceive('attempt')
        ->with([
            "email" => "test@test.com",
            "password" => "test",
            "status" => "active",
        ], false)
        ->andReturnFalse();

    $api = new ApiAuthentication();
    $api->login($credentials);
})->expectException(AuthenticationException::class);

it("Check that it throws an exception when the registered user with the name is not found.", function () {
    $credentials = [
        "username" => "test",
        "password" => "test",
        "status" => "active",
        "remember" => false,
    ];
    Auth::shouldReceive('guard')
        ->andReturnSelf()
        ->shouldReceive('attempt')
        ->with([
            "name" => "test",
            "password" => "test",
            "status" => "active",
        ], false)
        ->andReturnFalse();

    $api = new ApiAuthentication();
    $api->login($credentials);
})->expectException(AuthenticationException::class);

it("Check when the login is successful.", function () {
    $credentials = [
        "username" => "test@test.com",
        "password" => "password",
        "status" => "active",
        "remember" => false,
    ];

    $api = $this->getMockBuilder(ApiAuthentication::class)
        ->disableOriginalConstructor()
        ->onlyMethods(["getToken"])
        ->getMock();

    $api->method("getToken")
        ->willReturn("1234");


    Auth::shouldReceive('guard')
        ->andReturnSelf()
        ->shouldReceive('user')
        ->andReturn(new User())
        ->shouldReceive('attempt')
        ->with([
            "email" => "test@test.com",
            "password" => "password",
            "status" => "active",
        ], false)
        ->andReturnTrue();

    $response = $api->login($credentials);
    expect($response["token_type"])->toBe("Bearer");
    expect($response["access_token"])->toBe("1234");
});
