<?php

namespace Tests\Unit\Http\Builder\Auth;

use App\Http\Builder\Auth\UserBuilder;
use App\Http\Data\Auth\UserData;
use App\UseCase\Status;
use PHPUnit\Framework\TestCase;

class UserDataTest extends TestCase
{
    private UserData $userData;

    public function setUp(): void
    {
        $this->userData = UserBuilder::builder()
            ->name("test")
            ->email("test@example.com")
            ->password("1234")
            ->status(Status::ACTIVE)
            ->tags(["test-tags"])->build();
    }

    /**
     * @testdox Check that it is an instance of UserData.
     * @test
     */
    public function checkInstanceTest()
    {
        $this->assertInstanceOf(UserData::class, UserBuilder::builder()->build());
    }

    /**
     * @testdox Check that it returns the value of name.
     * @test
     */
    public function nameTest()
    {
        $this->assertEquals("test", $this->userData->getName());
    }

    /**
     * @testdox check that it returns the value of email.
     * @test
     */
    public function emailTest()
    {
        $this->assertEquals("test@example.com", $this->userData->getEmail());
    }

    /**
     * @testdox check that it returns the value of password.
     * @test
     */
    public function passwordTest()
    {
        $this->assertEquals("1234", $this->userData->getPassword());
    }

    /**
     * @testdox check that it returns the value of status.
     * @test
     */
    public function statusTest()
    {
        $this->assertEquals(Status::ACTIVE, $this->userData->getStatus());
    }

    /**
     * @testdox check that it returns the array of tags.
     * @test
     */
    public function tagsTest()
    {
        $this->assertEquals(["test-tags"], $this->userData->getTags());
    }
}
