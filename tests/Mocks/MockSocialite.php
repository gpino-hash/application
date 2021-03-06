<?php


namespace Tests\Mocks;

use Laravel\Socialite\Facades\Socialite;
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
        $abstractUser = \Mockery::mock('Laravel\Socialite\Two\User');

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

        $provider = \Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('stateless')
            ->andReturnSelf()
            ->shouldReceive('user')
            ->andReturn($abstractUser);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn($provider);

        return $provider;
    }
}