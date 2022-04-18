<?php

namespace App\Factory\Auth\Impl;

use App\Factory\Auth\IApi;
use App\Http\Builder\Auth\ResetPasswordData;
use App\Http\Builder\Auth\UserData;
use App\Models\User;
use App\Notifications\ActiveUserNotification;
use App\Repository\User\ICreateUser;
use App\UseCase\AuthenticatorResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use function resolve;
use function throw_unless;

class Api implements IApi
{
    use AuthenticatorResponse;

    /**
     * @inheritDoc
     * @throws \Throwable
     */
    #[ArrayShape(["access_token" => "string", "token_type" => "string", "user" => "\App\Http\Resources\UserResource"])]
    public function login(string $guardName, UserData $userData, bool $remember): array
    {
        $key = filter_var($userData->name, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        $credentials = [
            $key => $userData->name,
            "password" => $userData->password,
            "status" => $userData->status,
        ];
        throw_unless(Auth::guard($guardName)->attempt($credentials, $remember), AuthenticationException::class);
        return $this->responseLogin($guardName);
    }

    /**
     * @inheritDoc
     */
    public function register(UserData $userData, ...$exclude): User
    {
        $rememberToken = self::generate();
        $user = resolve(ICreateUser::class)->create(array_merge($userData->getAttributeArray($exclude),
            ["remember_token" => $rememberToken,]));
        $user->notify(new ActiveUserNotification($rememberToken, $userData->name));
        return $user;
    }

    /**
     * @inheritDoc
     */
    public function forgot(string $email): array
    {
        $status = Password::sendResetLink(["email" => $email]);
        return $this->responsePassword($status === Password::RESET_LINK_SENT, $status);
    }

    /**
     * @inheritDoc
     */
    public function reset(ResetPasswordData $passwordData): array
    {
        $status = Password::reset(
            $passwordData->getAttributes(),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $this->responsePassword($status === Password::PASSWORD_RESET, $status);
    }

    /**
     * @param bool $isPassword
     * @param string $status
     * @return array
     */
    private function responsePassword(bool $isPassword, string $status): array
    {
        return $isPassword ? ['status' => __($status)] : ['email' => __($status)];
    }
}
