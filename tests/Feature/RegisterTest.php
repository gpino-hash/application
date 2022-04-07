<?php

namespace Tests\Feature;

use App\Factory\Auth\Impl\Api;
use App\Models\User;
use App\Repository\User\ICreateUser;
use App\UseCase\Status;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @testdox Check when null data arrives.
     * @test
     */
    public function caseOne()
    {
        $response = $this->json(Request::METHOD_POST, "api/auth/register", [
            "email" => null,
            "password" => null,
            "name" => null,
            "password_confirmation" => null,
        ]);

        $response->assertJsonValidationErrors(["name", "password", "email", "password_confirmation"]);
    }

    /**
     * @testdox Check when the email exists skip the validation.
     * @test
     */
    public function caseTwo()
    {
        $user = User::factory()->create();

        $response = $this->json(Request::METHOD_POST, "api/auth/register", [
            "email" => $user->email,
            "password" => "Dwert1234*",
            "name" => "test",
            "password_confirmation" => "Dwert1234*",
        ]);

        $response->assertJsonValidationErrors(["email"]);
        $response->assertJsonMissingValidationErrors(["password", "name", "password_confirmation"]);
    }

    /**
     * @testdox Check when the nickname exists skip validation.
     * @test
     */
    public function caseThree()
    {
        $user = User::factory()->create();

        $response = $this->json(Request::METHOD_POST, "api/auth/register", [
            "email" => "test@example.com",
            "password" => "Dwert1234*",
            "name" => $user->name,
            "password_confirmation" => "Dwert1234*",
        ]);

        $response->assertJsonValidationErrors(["name"]);
        $response->assertJsonMissingValidationErrors(["password", "email", "password_confirmation"]);
    }

    /**
     * @testdox Check for validation errors.
     * @test
     */
    public function caseFour()
    {
        $response = $this->json(Request::METHOD_POST, "api/auth/register", [
            "email" => "testexample.com",
            "password" => "12345678",
            "name" => null,
            "password_confirmation" => "Dwert1234*",
        ]);

        $response->assertJsonValidationErrors(["name", "password", "email", "password_confirmation"]);
    }

    /**
     * @testdox Check that you save the data correctly.
     * @test
     */
    public function caseFive()
    {
        Notification::fake();
        $data = [
            "email" => "test@example.com",
            "password" => "Dwert1234*",
            "name" => "test",
        ];

        $this->assertDatabaseMissing("users", [
            "email" => $data["email"],
        ]);
        $response = $this->json(Request::METHOD_POST, "api/auth/register", array_merge($data, [
            "password_confirmation" => "Dwert1234*",
        ]));
        $this->assertDatabaseHas("users", [
            "email" => $data["email"],
        ]);
        $response->assertJsonStructure([
            "success",
            "data",
            "message",
        ]);
    }

    /**
     * @testdox Check when you try to save a data it throws a ModeNotFoundException
     * @test
     */
    public function caseSix()
    {
        $data = [
            "email" => "test@example.com",
            "password" => "Dwert1234*",
            "name" => "test",
        ];

        Api::setTest("1234");

        $this->mock(ICreateUser::class)
            ->shouldReceive("create")
            ->with(["email" => $data["email"], "name" => $data["name"], "status" => Status::LOCKED->getName(), "remember_token" => "1234"])
            ->andThrow(ModelNotFoundException::class);

        $response = $this->json(Request::METHOD_POST, "api/auth/register", array_merge($data, [
            "password_confirmation" => "Dwert1234*",
        ]));

        $response->assertJson([
            "success" => false,
            "message" => "We are having difficulty saving your data. Please try again later."
        ]);
    }

    /**
     * @testdox Check when you try to save a data it throws an Exception
     * @test
     */
    public function caseSeven()
    {
        $data = [
            "email" => "test@example.com",
            "password" => "Dwert1234*",
            "name" => "test",
        ];

        Api::setTest("1234");

        $this->mock(ICreateUser::class)
            ->shouldReceive("create")
            ->with(["email" => $data["email"], "name" => $data["name"], "status" => Status::LOCKED->getName(), "remember_token" => "1234"])
            ->andThrow(\Exception::class);

        $response = $this->json(Request::METHOD_POST, "api/auth/register", array_merge($data, [
            "password_confirmation" => "Dwert1234*",
        ]));

        $response->assertJson([
            "success" => false,
            "message" => "We are currently having difficulties, please try again later."
        ]);
    }
}
