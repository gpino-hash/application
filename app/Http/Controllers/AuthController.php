<?php

namespace App\Http\Controllers;

use App\Factory\Auth\AbstractAuthFactory;
use App\Factory\Auth\GuardName;
use App\Factory\Auth\ISocialNetwork;
use App\Factory\Report\Impl\LevelType;
use App\Factory\Report\Impl\LoggerComponent;
use App\Factory\Report\Impl\LoggerFactory;
use App\Http\Builder\Auth\UserData;
use App\Http\Requests\AuthRequest;
use App\Http\Traits\ResponseWithHttpStatus;
use App\UseCase\Status;
use App\UseCase\TypeSocialNetworks;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class AuthController extends Controller
{
    use ResponseWithHttpStatus;

    /**
     * @param AbstractAuthFactory $abstractFactory
     * @param ISocialNetwork $socialNetworkAuthentication
     */
    public function __construct(private AbstractAuthFactory $abstractFactory,
                                private ISocialNetwork $socialNetworkAuthentication)
    {
        $this->middleware("guest");
    }

    /**
     * @param AuthRequest $request
     * @param string|null $socialNetwork
     * @return Application|ResponseFactory|Response
     */
    public function login(AuthRequest $request, string $socialNetwork = null): Application|ResponseFactory|Response
    {
        try {
            if ($request->isMethod("POST")) {
                return $this->success(__('auth.authenticated'),
                    $this->abstractFactory->api()->login(GuardName::WEB,
                        $this->getLoginData($request),
                        $request->boolean("remember")));
            }
            return $this->success(__('auth.authenticated'),
                $this->abstractFactory->socialNetwork()->handle(GuardName::WEB,
                    TypeSocialNetworks::fromValue($socialNetwork)->value));
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
     * @param Request $request
     * @return UserData
     */
    private function getLoginData(Request $request): UserData
    {
        $builder = new UserData();
        $builder->email = $request->input("email");
        $builder->name = $request->input("username");
        $builder->password = $request->input("password");
        $builder->status = Status::fromValue($request->input("status", "locked"))->value;
        return $builder->build();
    }
}
