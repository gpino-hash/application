<?php

namespace Tests\Feature;

use App\Factory\Auth\IAuthenticate;
use App\Factory\Auth\Impl\ApiAuthentication;
use App\Models\User;
use App\Repository\User\IUser;
use App\UseCase\Status;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use function Pest\Faker\faker;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

uses(DatabaseTransactions::class);

it("Check when null data arrives.")
    ->json(Request::METHOD_POST, "api/auth/register", [
        "email" => null,
        "password" => null,
        "name" => null,
        "password_confirmation" => null,
    ])->assertJsonValidationErrors(["name", "password", "email", "password_confirmation"]);

it("Check when the email exists skip the validation.", function () {
    $user = User::factory()->create();

    $response = $this->json(Request::METHOD_POST, "api/auth/register", [
        "email" => $user->email,
        "password" => "Dwert1234*",
        "name" => faker()->name,
        "password_confirmation" => "Dwert1234*",
    ]);
    $response->assertJsonValidationErrors(["email"]);
    $response->assertJsonMissingValidationErrors(["password", "name", "password_confirmation"]);
});

it("Check when the nickname exists skip validation.", function () {
    $user = User::factory()->create();

    $response = $this->json(Request::METHOD_POST, "api/auth/register", [
        "email" => "test@example.com",
        "password" => "Dwert1234*",
        "name" => $user->name,
        "password_confirmation" => "Dwert1234*",
    ]);

    $response->assertJsonValidationErrors(["name"]);
    $response->assertJsonMissingValidationErrors(["password", "email", "password_confirmation"]);
});

it("Check for validation errors.")
    ->json(Request::METHOD_POST, "api/auth/register", [
        "email" => "testexample.com",
        "password" => "12345678",
        "name" => null,
        "password_confirmation" => "Dwert1234*",
    ])
    ->assertJsonValidationErrors(["name", "password", "email", "password_confirmation"]);

it("Check that you save the data correctly.", function () {
    Notification::fake();
    $data = [
        "email" => faker()->safeEmail(),
        "password" => "Dwert1234*",
        "name" => faker()->name,
    ];

    assertDatabaseMissing("users", [
        "email" => $data["email"],
    ]);

    $this->json(Request::METHOD_POST, "api/auth/register", array_merge($data, [
        "password_confirmation" => "Dwert1234*",
    ]))
        ->assertCreated()
        ->assertJsonStructure([
            "success",
            "data",
            "message",
        ]);

    assertDatabaseHas("users", [
        "email" => $data["email"],
    ]);
});

it("Check when you try to save a data it throws a ModeNotFoundException.", function () {
    $data = [
        "email" => faker()->safeEmail(),
        "password" => "Dwert1234*",
        "name" => faker()->name,
    ];

    ApiAuthentication::setTest("1234");

    \Pest\Laravel\mock(IAuthenticate::class)
        ->shouldReceive("register")
        ->with(["email" => $data["email"], "password" => "Dwert1234*", "name" => $data["name"], "status" => Status::LOCKED,])
        ->andThrow(ModelNotFoundException::class);

    $response = $this->json(Request::METHOD_POST, "api/auth/register", array_merge($data, [
        "password_confirmation" => "Dwert1234*",
    ]));

    $response->assertJson([
        "success" => false,
        "message" => "We are having difficulty saving your data. Please try again later."
    ]);
});

it("Check when you try to save a data it throws an Exception.", function () {
    $data = [
        "email" => faker()->safeEmail(),
        "password" => "Dwert1234*",
        "name" => faker()->name,
    ];

    ApiAuthentication::setTest("1234");

    \Pest\Laravel\mock(IAuthenticate::class)
        ->shouldReceive("register")
        ->with(["email" => $data["email"], "name" => $data["name"], "status" => Status::LOCKED,])
        ->andThrow(\Exception::class);

    $response = $this->json(Request::METHOD_POST, "api/auth/register", array_merge($data, [
        "password_confirmation" => "Dwert1234*",
    ]));

    $response->assertJson([
        "success" => false,
        "message" => "We are currently having difficulties, please try again later."
    ]);
});
