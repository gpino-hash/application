<?php

namespace Tests\Feature;

use App\Http\Resources\UserResource;
use App\Http\UseCase\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Tests\MockSocialite;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions, MockSocialite;

    /**
     * @testdox check when parameters are null return validation.
     * @test
     */
    public function caseOne()
    {
        $response = $this->json(Request::METHOD_POST, "api/auth/login", [
            "username" => null,
            "password" => null,
            "status" => "active",
        ]);

        $response->assertJsonValidationErrors(["username", "password"]);
    }

    /**
     * @testdox Check when the user does not match to start session returns an error
     * @test
     */
    public function caseTwo()
    {
        $user = User::factory()->make();
        $credential = [
            "username" => $user->email,
            "password" => "password",
            "status" => "active",
        ];
        $response = $this->json(Request::METHOD_POST, "api/auth/login", $credential);
        $response->assertJsonMissingValidationErrors(["username", "password"]);
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
    public function caseThree()
    {
        $user = User::factory()->create();
        $credential = [
            "username" => $user->email,
            "password" => "password",
            "status" => Status::ACTIVE->name,
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

    /**
     * @testdox Check when the user does not match to start session returns an error
     * @test
     */
    public function caseFour()
    {
        $user = User::factory()->inactive()->create();
        $credential = [
            "username" => $user->email,
            "password" => "password",
            "status" => Status::ACTIVE->getName(),
        ];
        $response = $this->json(Request::METHOD_POST, "api/auth/login", $credential);
        $response->assertJsonMissingValidationErrors(["username", "password"]);
        $this->assertInvalidCredentials([
            "email" => $user->email,
            "password" => "password",
            "status" => Status::ACTIVE->getName(),
        ]);
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
    public function caseFive()
    {
        $user = User::factory()->create();
        $credential = [
            "username" => $user->name,
            "password" => "password",
            "status" => Status::ACTIVE->name,
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

    /**
     * @testdox Check that the redirect works correctly.
     * @test
     */
    public function caseSix()
    {
        $socialNetwork = "google";
        Socialite::shouldReceive('driver')
            ->with($socialNetwork)
            ->once();
        $this->call(Request::METHOD_GET, "auth/" . $socialNetwork . "/login")->isRedirection();
    }

    /**
     * @testdox Check the authentication of the user by social networks.
     * @test
     */
    public function caseSeven()
    {
        $user = User::factory()->create();
        $this->mockSocialite($user->email);
        $response = $this->json(Request::METHOD_GET, "api/auth/login/google/callback");
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
