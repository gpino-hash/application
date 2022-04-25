<?php

namespace Tests\Unit\Http\Factory\Auth\Impl;

use App\Factory\Auth\IAuthenticate;
use App\Factory\Auth\Impl\ApiAuthentication;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repository\User\IUser;
use App\UseCase\SocialNetworkType;
use App\UseCase\Status;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Tests\MockSocialite;
use Tests\TestCase;

class SocialNetworkTest extends TestCase
{
    use DatabaseTransactions, MockSocialite;

    /**
     * @testdox Check when the user does not exist the new user is created.
     * @test
     */
    public function caseOne()
    {
        Notification::fake();

        $user = User::factory()->make();
        $socialMediaUser = $this->mockSocialite($user->email)->user();

        ApiAuthentication::setTest("1234");

        mock(User::class)
            ->shouldReceive("getUserByEmail")
            ->with($user->email, Status::ACTIVE)
            ->andReturnNull();

        $this->mock(IAuthenticate::class)
            ->shouldReceive("register")
            ->with(["email" => $user->email, "name" => $socialMediaUser->getNickName(), "status" => Status::LOCKED,])
            ->andReturn($user);

        ApiAuthentication::setTest("1234");

        $api = $this->mockAuthSocialite($user);
        $response = $api->login(SocialNetworkType::GOOGLE);

        $this->assertEquals("Bearer", $response["token_type"]);
        $this->assertEquals("1234", $response["access_token"]);
    }

    /**
     * @testdox Check when the social network login is successful.
     * @test
     */
    public function caseTwo()
    {
        $user = User::factory()->create();

        $this->mockSocialite($user->email);
        $api = $this->mockAuthSocialite($user);

        ApiAuthentication::setTest("1234");

        $this->mock(User::class)
            ->shouldReceive("getUserByEmail")
            ->with($user->email, Status::ACTIVE)
            ->andReturn($user);

        $response = $api->login(SocialNetworkType::GOOGLE);

        $this->assertEquals(new UserResource($user), $response["user"]);
        $this->assertEquals("Bearer", $response["token_type"]);
        $this->assertEquals("1234", $response["access_token"]);
    }
}
