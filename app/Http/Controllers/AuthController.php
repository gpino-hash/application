<?php

namespace App\Http\Controllers;

use App\Http\Factory\Auth\GuardName;
use App\Http\Factory\Auth\Impl\ApiAuthentication;
use App\Http\Factory\Auth\Impl\SocialNetworkAuthentication;
use App\Http\Requests\AuthRequest;
use App\Http\Traits\ResponseWithHttpStatus;
use App\Http\UseCase\TypeSocialNetworks;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class AuthController extends Controller
{
    use ResponseWithHttpStatus;

    /**
     * @param AuthRequest $request
     * @return Application|ResponseFactory|Response
     */
    public function login(AuthRequest $request): Application|ResponseFactory|Response
    {
        try {
            $api = new ApiAuthentication(GuardName::WEB,
                $request->only("email", "password", "status"),
                $request->boolean("remember"));
            return $this->success("Request made successfully.", $api->handle());
        } catch (AuthenticationException $authenticationException) {
            return $this->failure("Failed to authenticate. User or password is wrong.");
        } catch (Throwable $exception) {
            return $this->failure("We are currently having difficulties, please try again later.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param string $socialNetwork
     * @return Application|Response|ResponseFactory
     */
    public function loginWithSocialNetwork(string $socialNetwork): Application|ResponseFactory|Response
    {
        try {
            $social = new SocialNetworkAuthentication(GuardName::WEB,
                TypeSocialNetworks::getTypeSocialNetworks($socialNetwork));
            return $this->success("Request made successfully.", $social->handle());
        } catch (AuthenticationException $authenticationException) {
            return $this->failure("Failed to authenticate. User or password is wrong.");
        } catch (Throwable $exception) {
            return $this->failure("We are currently having difficulties, please try again later.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param string $socialNetwork
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|RedirectResponse
     */
    public function redirect(string $socialNetwork): \Symfony\Component\HttpFoundation\RedirectResponse|RedirectResponse
    {
        return Socialite::driver($socialNetwork)->redirect();
    }
}
