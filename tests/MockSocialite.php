<?php

namespace Tests;

use Laravel\Socialite\Facades\Socialite;

trait MockSocialite
{
    /**
     * @param string $email
     * @return void
     */
    public function mockSocialite(string $email = 'test.user' . '@gmail.com'): void
    {
        $abstractUser = \Mockery::mock('Laravel\Socialite\Two\User');

        $abstractUser
            ->shouldReceive('getId')
            ->andReturn(rand())
            ->shouldReceive('getName')
            ->andReturn('test user')
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
    }
}
