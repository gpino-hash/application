<?php

namespace Tests\Unit\Http\Factory\Auth\Impl;

use App\Factory\Auth\GuardName;
use App\Factory\Auth\Impl\Api;
use App\Factory\Auth\Impl\SocialNetwork;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\ActiveUserNotification;
use App\Repository\User\ICreateUser;
use App\Repository\User\IUserByEmail;
use App\UseCase\Status;
use App\UseCase\TypeSocialNetworks;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Tests\MockSocialite;
use Tests\TestCase;

class SocialNetworkTest extends TestCase
{
    use MockSocialite;

    /**
     * @testdox Check when the user does not exist the new user is created.
     * @test
     */
    public function caseOne()
    {
        Notification::fake();

        $user = User::factory()->make();
        $socialMediaUser = $this->mockSocialite($user->email)->user();

        $this->mock(IUserByEmail::class)
            ->shouldReceive("getUserByEmail")
            ->with($user->email, Status::ACTIVE)
            ->andReturnNull();

        Api::setTest("1234");

        $this->mock(ICreateUser::class)
            ->shouldReceive("create")
            ->with(["email" => $user->email, "name" => $socialMediaUser->getNickName(), "status" => Status::LOCKED->getName(), "remember_token" => "1234"])
            ->andReturn($user);

        $api = $this->mockAuthSocialite($user);
        $response = $api->handle(GuardName::WEB, TypeSocialNetworks::GOOGLE);

        Notification::assertSentTo([$user], ActiveUserNotification::class);

        $this->assertEquals("Bearer", $response["token_type"]);
        $this->assertEquals("1234", $response["access_token"]);
    }

    /**
     * @testdox Check when the social network login is successful.
     * @test
     */
    public function caseTwo()
    {
        $user = User::factory()->make();

        $this->mockSocialite($user->email);
        $api = $this->mockAuthSocialite($user);

        $this->mock(IUserByEmail::class)
            ->shouldReceive("getUserByEmail")
            ->with($user->email, Status::ACTIVE)
            ->andReturn($user);

        $response = $api->handle(GuardName::WEB, TypeSocialNetworks::GOOGLE);

        $this->assertEquals(new UserResource($user), $response["user"]);
        $this->assertEquals("Bearer", $response["token_type"]);
        $this->assertEquals("1234", $response["access_token"]);
    }

    /**
     * @param User $user
     * @return SocialNetwork|\PHPUnit\Framework\MockObject\MockObject
     */
    private function mockAuthSocialite(User $user)
    {
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

        return $api;
    }
}
