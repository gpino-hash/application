<?php

namespace Tests\Unit\Http\Builder\Auth;

use App\Http\Builder\Auth\UserData;
use App\UseCase\Status;

beforeEach(function () {
    $user = new UserData();
    $user->name = "test";
    $user->email = "test@example.com";
    $user->password = "1234";
    $user->status = Status::ACTIVE;
    $user->tags = ["test-tags"];
    $this->userData = $user->build();
});

it("Check that it returns the user data", function () {
    expect($this->userData->name)->toBe("test");
    expect($this->userData->email)->toBe("test@example.com");
    expect($this->userData->password)->toBe("1234");
    expect($this->userData->status)->toBe(Status::ACTIVE);
    expect($this->userData->tags)->toBe(["test-tags"]);
});

