<?php

namespace Tests\Feature;

use App\Enums\Status;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Tests\Mocks\MockSocialite;

uses(DatabaseTransactions::class, MockSocialite::class);

it("check when parameters are null return validation.", function () {
    $response = $this->json(Request::METHOD_POST, "api/auth/login", [
        "username" => null,
        "password" => null,
        "status" => "active",
        "remember" => false,
    ]);

    $response->assertJsonValidationErrors(["username", "password"]);
})->group("Api");

it("Check when the user does not exist, does not log in.", function () {
    $user = User::factory()->make();
    $credential = [
        "username" => $user->email,
        "password" => "password",
        "status" => "active",
        "remember" => false,
    ];
    $response = $this->json(Request::METHOD_POST, "api/auth/login", $credential);
    $response->assertJsonMissingValidationErrors(["username", "password"]);
    $response->assertJsonStructure([
        "success",
        "message",
    ]);

    expect($response->json("success"))->toBeFalse();
    expect($response->json("message"))->toBe("These credentials do not match our records.");
})->group("Api");

it("Check when we enter the correct email and password log in.", function () {
    $user = User::factory()->active()->create();
    $credential = [
        "username" => $user->email,
        "password" => "password",
        "status" => Status::ACTIVE,
        "remember" => false,
    ];
    $response = $this->json(Request::METHOD_POST, "api/auth/login", $credential);
    $this->assertAuthenticatedAs($user, "web");
    $response->assertJsonStructure([
        "success",
        "data",
        "message",
    ]);
    $userResource = new UserResource($user);
    $data = $response->json();
    expect($data["success"])->toBeTrue();
    expect($data["message"])->toBe("Request made successfully.");
    expect($data["data"]["user"])->toBe($userResource->toArray(null));
})->group("Api");

it("Check when the user does not match to start session returns an error", function () {
    $user = User::factory()->inactive()->create();
    $credential = [
        "username" => $user->email,
        "password" => "password",
        "status" => Status::ACTIVE,
        "remember" => false,
    ];
    $response = $this->json(Request::METHOD_POST, "api/auth/login", $credential);
    $response->assertJsonMissingValidationErrors(["username", "password"]);
    $this->assertInvalidCredentials([
        "email" => $user->email,
        "password" => "password",
        "status" => Status::ACTIVE,
    ]);
    $response->assertJsonStructure([
        "success",
        "message",
    ]);

    expect($response->json("success"))->toBeFalse();
    expect($response->json("message"))->toBe("These credentials do not match our records.");
})->group("Api");

it("Check when we enter the correct username and password log in.", function () {

    $user = User::factory()->active()->create();
    $credential = [
        "username" => $user->name,
        "password" => "password",
        "status" => Status::ACTIVE,
        "remember" => false,
    ];
    $response = $this->json(Request::METHOD_POST, "api/auth/login", $credential);
    $response->assertJsonStructure([
        "success",
        "data",
        "message",
    ]);
    $userResource = new UserResource($user);
    $data = $response->json();
    expect($data["success"])->toBeTrue();
    expect($data["message"])->toBe("Request made successfully.");
    expect($data["data"]["user"])->toBe($userResource->toArray(null));
})->group("Api");

it("Check that it throws an error when trying to log in.", function () {
    $user = User::factory()->create();
    $credential = [
        "username" => $user->email,
        "password" => "password",
        "status" => Status::ACTIVE,
    ];

    $response = $this->json(Request::METHOD_POST, "api/auth/login", $credential);
    expect($response->json("success"))->toBeFalse();
    expect($response->json("message"))->toBe("We are currently having difficulties, please try again later.");
})->group("Api");

it("Check that the redirect works correctly.", function () {
    $socialNetwork = "google";
    Socialite::shouldReceive('driver')
        ->with($socialNetwork)
        ->once();
    $this->call(Request::METHOD_GET, "auth/" . $socialNetwork . "/redirect")->isRedirection();
})->group("Social Networks");

it("Check the authentication of the user by social networks.", function () {
    $user = User::factory()->active()->create();
    $this->mockSocialite($user->email);
    $response = $this->json(Request::METHOD_GET, "api/auth/google/callback");
    $this->assertAuthenticatedAs($user);
    $response->assertJsonStructure([
        "success",
        "data",
        "message",
    ]);
    $userResource = new UserResource($user);
    $data = $response->json();
    expect($data["success"])->toBeTrue();
    expect($data["message"])->toBe("Request made successfully.");
    expect($data["data"]["user"])->toBe($userResource->toArray(null));
})->group("Social Networks");

it("Check the authentication of the user by social networks1.", function () {
    $user = User::factory()->active()->make();
    $this->mockSocialite($user->email);
    $response = $this->json(Request::METHOD_GET, "api/auth/google/callback");
    $this->assertAuthenticated();
    $response->assertJsonStructure([
        "success",
        "data",
        "message",
    ]);
    $data = $response->json();
    expect($data["success"])->toBeTrue();
    expect($data["message"])->toBe("Request made successfully.");
    expect($data["data"]["user"]["email"])->toBe($user->email);
})->group("Social Networks");