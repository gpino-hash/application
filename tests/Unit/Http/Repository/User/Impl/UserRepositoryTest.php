<?php

namespace Tests\Unit\Http\Repository\User\Impl;

use App\Http\Repository\User\IGetUser;
use App\Http\Repository\User\IGetUserByEmail;
use App\Http\Repository\User\Impl\UserRepository;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    /**
     * @testdox
     * @test
     */
    public function caseOne()
    {
        $repository = new UserRepository();
        $this->assertNull($repository->getUserByEmail("test"));
    }

    /**
     * @testdox
     * @test
     */
    public function caseTwo()
    {
        $user = User::factory()->make();

        $builder = $this->mock(Builder::class);
        $builder->shouldReceive("where")->with("email", $user->email)->andReturnSelf();
        $builder->shouldReceive('first')->andReturn($user);

        $mockUser = $this->mock(User::class);
        $mockUser->shouldReceive('query')->andReturn($builder);

        $repository = new UserRepository();
        $this->assertEquals($user, $repository->getUserByEmail($user->email));
    }

    /**
     * @testdox
     * @test
     */
    public function caseThree()
    {
        $this->assertInstanceOf(IGetUserByEmail::class, new UserRepository());
        $this->assertInstanceOf(IGetUser::class, new UserRepository());
    }
}
