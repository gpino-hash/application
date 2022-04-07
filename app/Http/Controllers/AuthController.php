<?php

namespace App\Http\Controllers;

use App\Factory\Auth\GuardName;
use App\Factory\Auth\IApi;
use App\Factory\Auth\ISocialNetwork;
use App\Http\Builder\Auth\UserBuilder;
use App\Http\Data\Auth\UserData;
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
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class AuthController extends Controller
{
    use ResponseWithHttpStatus;

    private IApi $apiAuthentication;

    private ISocialNetwork $socialNetworkAuthentication;

    /**
     * @param IApi $apiAuthentication
     * @param ISocialNetwork $socialNetworkAuthentication
     */
    public function __construct(IApi $apiAuthentication, ISocialNetwork $socialNetworkAuthentication)
    {
        $this->middleware("guest");
        $this->apiAuthentication = $apiAuthentication;
        $this->socialNetworkAuthentication = $socialNetworkAuthentication;
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
                return $this->success("Request made successfully.",
                    $this->apiAuthentication->login(GuardName::WEB, $this->getLoginData($request), $request->boolean("remember")));
            }
            return $this->success("Request made successfully.",
                $this->socialNetworkAuthentication->handle(GuardName::WEB, TypeSocialNetworks::getTypeSocialNetworks($socialNetwork)));
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

    /**
     * @param Request $request
     * @return UserData
     */
    private function getLoginData(Request $request): UserData
    {
        return UserBuilder::builder()
            ->username($request->input("username"))
            ->password($request->input("password"))
            ->status(Status::getUserStatus($request->input("status", "locked")))
            ->build();
    }
}
