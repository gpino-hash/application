<?php

namespace Tests\Feature;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @testdox check when the email is misspelled return the email validation.
     * @test
     */
    public function caseOne()
    {
        $response = $this->json(Request::METHOD_POST, "api/auth/login", [
            "email" => "test",
            "password" => "1234"
        ]);

        $response->assertJsonValidationErrors(["email"]);
        $response->assertJsonMissingValidationErrors(["password"]);
    }

    /**
     * @testdox check when parameters are null return validation.
     * @test
     */
    public function caseTwo()
    {
        $response = $this->json(Request::METHOD_POST, "api/auth/login", [
            "email" => null,
            "password" => null
        ]);

        $response->assertJsonValidationErrors(["email", "password"]);
    }

    /**
     * @testdox Check when the user does not match to start session returns an error
     * @test
     */
    public function caseThree()
    {
        $user = User::factory()->make();
        $credential = [
            "email" => $user->email,
            "password" => "password"
        ];
        $response = $this->json(Request::METHOD_POST, "api/auth/login", $credential);
        $response->assertJsonMissingValidationErrors(["email", "password"]);
        $this->assertInvalidCredentials($credential);
        $response->assertJsonStructure([
            "success",
            "message",
        ]);
        $this->assertEquals(false, $response->json("success"));
        $this->assertEquals("Failed to authenticate. User or password is wrong.", $response->json("message"));
    }

    /**
     * @testdox Check when we enter the correct username and password log in.
     * @test
     */
    public function caseFour()
    {
        $user = User::factory()->create();
        $credential = [
            "email" => $user->email,
            "password" => "password"
        ];
        $response = $this->json(Request::METHOD_POST, "api/auth/login", $credential);
        $this->assertAuthenticatedAs($user);
        $response->assertJsonStructure([
            "success",
            "data",
            "message",
        ]);
        $userResource = new UserResource($user);
        $data = $response->json();
        $this->assertEquals(true, $data["success"]);
        $this->assertEquals("Request made successfully.", $data["message"]);
        $this->assertEquals($userResource->toArray(null), $data["data"]["user"]);
    }
}
