<?php

namespace Tests\Unit\Http\Repository\User\Impl;

use App\Models\User;
use App\Repository\User\Impl\UserRepository;
use App\Repository\User\IUser;
use App\Repository\User\IUserByEmail;
use App\UseCase\Status;
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
        $this->assertNull($repository->getUserByEmail("test", Status::INACTIVE));
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
        $builder->shouldReceive("where")->with("status", Status::ACTIVE)->andReturnSelf();
        $builder->shouldReceive('first')->andReturn($user);

        $mockUser = $this->mock(User::class);
        $mockUser->shouldReceive('query')->andReturn($builder);

        $repository = new UserRepository();
        $this->assertEquals($user, $repository->getUserByEmail($user->email, Status::ACTIVE));
    }

    /**
     * @testdox
     * @test
     */
    public function caseThree()
    {
        $this->assertInstanceOf(IUserByEmail::class, new UserRepository());
        $this->assertInstanceOf(IUser::class, new UserRepository());
    }
}
