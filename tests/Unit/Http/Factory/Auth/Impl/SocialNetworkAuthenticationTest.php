<?php

namespace Tests\Unit\Http\Factory\Auth\Impl;


use App\Http\Factory\Auth\GuardName;
use App\Http\Factory\Auth\Impl\SocialNetwork;
use App\Http\Repository\User\IUserByEmail;
use App\Http\Resources\UserResource;
use App\Http\UseCase\Status;
use App\Http\UseCase\TypeSocialNetworks;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Tests\MockSocialite;
use Tests\TestCase;

class SocialNetworkAuthenticationTest extends TestCase
{

    use MockSocialite;

    /**
     * @testdox Check that it throws an exception when the registered user is not found.
     * @test
     */
    public function caseOne()
    {
        $this->expectException(AuthenticationException::class);

        $this->mockSocialite();

        $api = new SocialNetwork();
        $api->login(GuardName::WEB, TypeSocialNetworks::GOOGLE);
    }

    /**
     * @testdox Check when the social network login is successful.
     * @test
     */
    public function caseTwo()
    {
        $user = User::factory()->make();

        $this->mockSocialite($user->email);

        Auth::shouldReceive('guard')
            ->andReturnSelf()
            ->shouldReceive('user')
            ->andReturn($user)
            ->shouldReceive('login')
            ->with($user)
            ->andReturnTrue();

        $api = $this->getMockBuilder(SocialNetwork::class)
            ->disableOriginalConstructor()
            ->onlyMethods(["getToken"])
            ->getMock();

        $api->method("getToken")
            ->willReturn("1234");

        $this->mock(IUserByEmail::class)
            ->shouldReceive("getUserByEmail")
            ->with($user->email, Status::ACTIVE)
            ->andReturn($user);

        $response = $api->login(GuardName::WEB, TypeSocialNetworks::GOOGLE);

        $this->assertEquals(new UserResource($user), $response["user"]);
        $this->assertEquals("Bearer", $response["token_type"]);
        $this->assertEquals("1234", $response["access_token"]);
    }
}
