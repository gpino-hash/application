<?php

namespace Tests\Unit\Http\Factory\Auth\Impl;

use App\Http\Factory\Auth\GuardName;
use App\Http\Factory\Auth\Impl\ApiAuthentication;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ApiAuthenticationTest extends TestCase
{

    /**
     * @testdox Check that it throws an exception when the registered user is not found.
     * @test
     */
    public function caseOne()
    {
        $this->expectException(AuthenticationException::class);
        $credentials = [
            "email" => "test",
            "password" => "test",
        ];
        $api = new ApiAuthentication(GuardName::WEB, $credentials, false);
        $api->handle();
    }

    /**
     * @testdox Check when the login is successful.
     * @test
     */
    public function caseTwo()
    {
        $user = User::factory()->make();
        $credentials = [
            "email" => $user->email,
            "password" => "password",
        ];

        $api = $this->getMockBuilder(ApiAuthentication::class)
            ->setConstructorArgs([GuardName::WEB, $credentials, false])
            ->onlyMethods(["getToken"])
            ->getMock();

        $api->method("getToken")
            ->willReturn("1234");


        Auth::shouldReceive('guard')
            ->andReturnSelf()
            ->shouldReceive('user')
            ->andReturn($user)
            ->shouldReceive('attempt')
            ->with($credentials, false)
            ->andReturnTrue();

        $response = $api->handle();


        $this->assertEquals(new UserResource($user), $response["user"]);
        $this->assertEquals("Bearer", $response["token_type"]);
        $this->assertEquals("1234", $response["access_token"]);
    }
}
