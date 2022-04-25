<?php

namespace App\Factory\Auth\Impl;

use App\Factory\Auth\GuardName;
use App\Factory\Auth\IAuthenticate;
use App\Models\User;
use App\Notifications\ActiveUserNotification;
use App\Repository\User\IPassword;
use App\UseCase\AuthenticatorResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use function throw_unless;

class ApiAuthentication implements IAuthenticate
{
    use AuthenticatorResponse;

    /**
     * @inheritDoc
     * @throws \Throwable
     */
    #[ArrayShape(["access_token" => "string", "token_type" => "string", "user" => "\App\Http\Resources\UserResource"])]
    public function login(array|string $data, string $guardName = GuardName::WEB): array
    {
        $key = filter_var($data["username"], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        $data = Arr::add($data, $key, $data["username"]);
        throw_unless(Auth::guard($guardName)->attempt(Arr::only($data, [$key, "password", "status"]), $data["remember"]),
            AuthenticationException::class);
        return $this->responseLogin($guardName);
    }

    /**
     * @inheritDoc
     */
    public function register(array $data): User
    {
        $rememberToken = self::generate();
        $user = User::create(array_merge($data,
            ["remember_token" => $rememberToken,]));
        $user->notify(new ActiveUserNotification($rememberToken, $data["name"]));
        return $user;
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
    public function reset(array $data): array
    {
        $status = Password::reset(
            $data,
            function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        return $this->passwordResponseMessage($status === Password::PASSWORD_RESET, $status);
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
}
