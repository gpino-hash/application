<?php

namespace Tests\Unit\Http\Factory\Auth\Impl;

use App\Factory\Auth\GuardName;
use App\Factory\Auth\Impl\Api;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Tests\Mock\MockAuthenticate;
use Tests\TestCase;

class ApiAuthenticationTest extends TestCase
{
    use MockAuthenticate;

    /**
     * @testdox Check that it throws an exception when the registered user is not found.
     * @test
     */
    public function caseOne()
    {
        $this->expectException(AuthenticationException::class);
        $credentials = [
            "username" => "test",
            "password" => "test",
            "status" => "active",
        ];
        $api = new Api();
        $api->login(GuardName::WEB, $this->mockLoginData($credentials), false);
    }

    /**
     * @testdox Check when the login is successful.
     * @test
     */
    public function caseTwo()
    {
        $user = User::factory()->make();
        $credentials = [
            "username" => $user->email,
            "password" => "password",
            "status" => "active",
        ];

        $api = $this->getMockBuilder(Api::class)
            ->disableOriginalConstructor()
            ->onlyMethods(["getToken"])
            ->getMock();

        $api->method("getToken")
            ->willReturn("1234");


        Auth::shouldReceive('guard')
            ->andReturnSelf()
            ->shouldReceive('user')
            ->andReturn($user)
            ->shouldReceive('attempt')
            ->with([
                "email" => $user->email,
                "password" => "password",
                "status" => "active",
            ], false)
            ->andReturnTrue();

        $response = $api->login(GuardName::WEB, $this->mockLoginData($credentials), false);


        $this->assertEquals(new UserResource($user), $response["user"]);
        $this->assertEquals("Bearer", $response["token_type"]);
        $this->assertEquals("1234", $response["access_token"]);
    }
}
