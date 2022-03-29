<?php

namespace App\Http\Controllers;

use App\Http\Factory\Auth\GuardName;
use App\Http\Factory\Auth\Impl\ApiAuthentication;
use App\Http\Factory\Auth\Impl\SocialNetworkAuthentication;
use App\Http\Requests\AuthRequest;
use App\Http\UseCase\TypeSocialNetworks;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * @param AuthRequest $request
     * @return JsonResponse
     */
    public function login(AuthRequest $request): JsonResponse
    {
        try {
            $api = new ApiAuthentication(GuardName::WEB,
                $request->only("email", "password"),
                $request->boolean("remember"));
            return response()->json($api->handle());
        } catch (AuthenticationException $authenticationException) {
            return response()->json("error al autenticarse");
        } catch (\Throwable $exception) {
            return response()->json("error al interno del servidor");
        }
    }

    /**
     * @param string $socialNetwork
     * @return JsonResponse
     */
    public function loginWithSocialNetwork(string $socialNetwork): JsonResponse
    {
        try {
            $social = new SocialNetworkAuthentication(GuardName::WEB,
                TypeSocialNetworks::getTypeSocialNetworks($socialNetwork));
            return response()->json($social->handle());
        } catch (AuthenticationException $authenticationException) {
            return response()->json("error al autenticarse");
        } catch (\Throwable $exception) {
            return response()->json("error al interno del servidor");
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
