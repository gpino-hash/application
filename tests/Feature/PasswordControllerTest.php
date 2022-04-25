<?php

namespace Tests\Feature;

use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

uses(DatabaseTransactions::class);

it("check when you don't receive the error email.", function () {
    $response = $this->json(Request::METHOD_POST, "api/auth/forgot-password", [
        "email" => null,
    ]);
    $response->assertJsonValidationErrors(["email"]);
});

it("check when the email is not registered error.", function () {
    $response = $this->json(Request::METHOD_POST, "api/auth/forgot-password", [
        "email" => "test@test.com",
    ]);
    expect($response->json("success"))->toBeFalse();
    expect($response->json("errors.email"))->toBe("We can't find a user with that email address.");
    expect($response->json("message"))->toBeEmpty();
});

it("Check that the email is registered and send the email to reset the password.", function () {
    Notification::fake();
    $user = User::factory()->create();
    $response = $this->json(Request::METHOD_POST, "api/auth/forgot-password", [
        "email" => $user->email,
    ]);

    expect($response->json("success"))->toBeTrue();
    expect($response->json("data.status"))->toBe("We have emailed your password reset link!");
});

it("Check when values are null throw error.", function () {
    $response = $this->json(Request::METHOD_POST, "api/auth/reset-password", [
        "password" => null,
        "password_confirmation" => null,
        "token" => null,
    ]);
    $response->assertJsonValidationErrors(["password", "token"]);
});

it("Check when the password and password_confirmation are not the same throw error.", function () {
    $response = $this->json(Request::METHOD_POST, "api/auth/reset-password", [
        "password" => "Desd1234*",
        "password_confirmation" => "Sfsdfd*",
        "token" => "qweqweqweq",
    ]);
    $response->assertJsonValidationErrors(["password"]);
});

it("Check that the password change is successful.", function () {
    $token = Str::random(60);
    $reset = PasswordReset::factory()->create([
        "token" => bcrypt($token),
    ]);
    User::factory()->create([
        "email" => $reset->email,
    ]);
    $response = $this->json(Request::METHOD_POST, "api/auth/reset-password", [
        "email" => $reset->email,
        "password" => "Desd1234*",
        "password_confirmation" => "Desd1234*",
        "token" => $token,
    ]);
    expect($response->json("success"))->toBeTrue();
    expect($response->json("data.status"))->toBe("Your password has been reset!");
});
