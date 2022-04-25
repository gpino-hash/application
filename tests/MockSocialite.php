<?php

namespace Tests;

use App\Factory\Auth\Impl\SocialMediaAuthentication;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;

trait MockSocialite
{
    /**
     * @param string $email
     * @return LegacyMockInterface|MockInterface|string
     */
    public function mockSocialite(string $email = 'test.user' . '@gmail.com'): LegacyMockInterface|MockInterface|string
    {
        $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');

        $abstractUser
            ->shouldReceive('getId')
            ->andReturn(rand())
            ->shouldReceive('getName')
            ->andReturn('test user')
            ->shouldReceive('getNickName')
            ->andReturn('testUser')
            ->shouldReceive('getEmail')
            ->andReturn($email)
            ->shouldReceive('getAvatar')
            ->andReturn('https://en.gravatar.com/userimage');

        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('stateless')
            ->andReturnSelf()
            ->shouldReceive('user')
            ->andReturn($abstractUser);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn($provider);

        return $provider;
    }

    /**
     * @param User $user
     * @return SocialMediaAuthentication|\PHPUnit\Framework\MockObject\MockObject
     */
    public function mockAuthSocialite(User $user)
    {
        Auth::shouldReceive('guard')
            ->andReturnSelf()
            ->shouldReceive('user')
            ->andReturn($user)
            ->shouldReceive('loginUsingId')
            ->with($user->id)
            ->andReturnTrue();

        $api = $this->getMockBuilder(SocialMediaAuthentication::class)
            ->disableOriginalConstructor()
            ->onlyMethods(["getToken"])
            ->getMock();

        $api->method("getToken")
            ->willReturn("1234");

        return $api;
    }
}
