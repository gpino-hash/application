<?php

namespace App\Http\Controllers;

use App\Factory\Auth\IAbstractAuthFactory;
use App\Http\Requests\AuthRequest;
use App\Http\Traits\ResponseWithHttpStatus;
use App\Http\Traits\TryAccess;
use App\UseCase\AuthenticationType;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class AuthController extends Controller
{
    use ResponseWithHttpStatus, TryAccess;

    public function __construct(private IAbstractAuthFactory $authFactory)
    {
    }

    /**
     * @param AuthRequest $request
     * @param string|null $socialNetwork
     * @return Application|ResponseFactory|Response
     */
    public function login(AuthRequest $request, string $socialNetwork = null): Application|ResponseFactory|Response
    {
        try {
            if ($request->isMethod(Request::METHOD_POST)) {
                return $this->authenticate($request->only([
                    "username",
                    "email",
                    "password",
                    "status",
                    "remember",
                ]));
            }
            return $this->authenticate($socialNetwork, AuthenticationType::SOCIAL_NETWORK);
        } catch (AuthenticationException $exception) {
            Log::stack(["stack"])->warning($exception->getMessage(), [$exception]);
            return $this->failure(__("auth.failed"));
        } catch (Throwable $exception) {
            Log::stack(["stack"])->emergency($exception->getMessage(),
                array_merge($request->only("email"), [$exception]));
            return $this->failure(__("errors.error"),
                $request->only("email"), Response::HTTP_INTERNAL_SERVER_ERROR);
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

    /**
     * @param array|string $data
     * @param string $type
     * @return Application|ResponseFactory|Response
     */
    private function authenticate(array|string $data, string $type = AuthenticationType::API): Application|ResponseFactory|Response
    {
        return $this->success(__('auth.authenticated'), $this->tryAccessAuth($type)->login($data));
    }
}
