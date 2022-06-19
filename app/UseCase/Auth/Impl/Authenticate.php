<?php


namespace App\UseCase\Auth\Impl;

use App\DataTransferObjects\UserData;
use App\Enums\Status;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\UseCase\Auth\IAuthenticate;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class Authenticate implements IAuthenticate
{
    private string $token = "application";

    static mixed $testToken = null;

    /**
     * @inheritDoc
     * @throws Throwable
     */
    #[ArrayShape(["access_token" => "string", "token_type" => "string", "user" => "\App\Http\Resources\UserResource"])]
    public function login(string|UserData $data, string $guardName = "web"): array
    {
        is_string($data) ? $this->initialSessionBySocialNetwork($data, $guardName)
            : $this->initialSession($data, $guardName);
        return $this->response();
    }

    /**
     * @inheritDoc
     */
    public function register(UserData $data): Model|Builder
    {
        return User::query()->create($data->toArray());
    }

    /**
     * @inheritDoc
     */
    public function forgot(string $email): array
    {
        $status = Password::sendResetLink(["email" => $email]);
        return $this->passwordResponseMessage($status === Password::RESET_LINK_SENT, $status);
    }

    /**
     * @inheritDoc
     */
    public function reset(UserData $data): array
    {
        $status = Password::reset(
            $data->include("email", "password", "password_confirmation", "token")->toArray(),
            function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        return $this->passwordResponseMessage($status === Password::PASSWORD_RESET, $status);
    }

    /**
     * @return mixed
     */
    public static function generate(): mixed
    {
        return static::$testToken ?: Str::random(60);
    }

    /**
     * @param $string
     * @return void
     */
    public static function setTest($string)
    {
        static::$testToken = $string;
    }

    /**
     * @param bool $isPassword
     * @param string $status
     * @return array
     */
    private function passwordResponseMessage(bool $isPassword, string $status): array
    {
        return $isPassword ? ['status' => __($status)] : ['email' => __($status)];
    }

    /**
     * @param $user
     * @param $password
     */
    private function resetPassword($user, $password): void
    {
        $user->forceFill([
            'password' => Hash::make($password)
        ])->setRememberToken(Str::random(60));
        $user->save();
        event(new PasswordReset($user));
    }

    /**
     * @param string $guardName
     * @return string
     */
    private function getToken(string $guardName): string
    {
        return Auth::guard($guardName)->user()->createToken($this->token)->plainTextToken;
    }

    /**
     * @param string $data
     * @param string $guardName
     */
    private function initialSessionBySocialNetwork(string $data, string $guardName): void
    {
        $socialMedia = Socialite::driver($data)->user();
        $user = User::query()->getUserByEmail($socialMedia->getEmail(), Status::ACTIVE);
        if (empty($user)) {
            $user = User::query()->create([
                "name" => $socialMedia->getNickname(),
                "email" => $socialMedia->getEmail(),
                "status" => Status::ACTIVE,
            ]);
        }
        Auth::guard($guardName)->loginUsingId($user->id);
    }

    /**
     * @param UserData $data
     * @param string $guardName
     * @throws Throwable
     */
    private function initialSession(UserData $data, string $guardName): void
    {
        throw_unless(Auth::guard($guardName)->attempt($data->only("name", "email", "password", "status"),
            $data->getValue("remember")), AuthenticationException::class);
    }

    /**
     * @param string $guardName
     * @return array
     */
    #[ArrayShape(["access_token" => "string", "token_type" => "string", "user" => "\App\Http\Resources\UserResource"])]
    private function response(string $guardName = "web"): array
    {
        return [
            "access_token" => $this->getToken($guardName),
            "token_type" => "Bearer",
            "user" => new UserResource(Auth::guard($guardName)->user()),
        ];
    }
}