<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\UserData;
use App\Enums\Status;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\UseCase\Auth\IAuthenticate;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class AuthController extends Controller
{

    private IAuthenticate $authenticate;

    /**
     * AuthController constructor.
     * @param IAuthenticate $authenticate
     */
    public function __construct(IAuthenticate $authenticate)
    {
        $this->authenticate = $authenticate;
        $this->middleware("guest");
    }

    /**
     * @param AuthRequest $request
     * @param string|null $socialNetwork
     * @return JsonResponse
     */
    public function Login(AuthRequest $request, string $socialNetwork = null): JsonResponse
    {
        try {
            $data = $socialNetwork;
            if ($request->isMethod(Request::METHOD_POST)) {
                $key = filter_var($request->input("username"), FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
                $request->merge([
                    "status" => Status::ACTIVE,
                    $key => $request->input("username"),
                ]);
                $data = UserData::fromRequest($request)->additional($request->only("username", "remember"));
            }
            return $this->sendResponse($this->authenticate->login($data), __('auth.authenticated'));
        } catch (AuthenticationException $exception) {
            Log::stack(["stack"])->warning($exception->getMessage(), [$exception]);
            return $this->sendError(__("auth.failed"));
        } catch (Throwable $exception) {
            Log::stack(["stack"])->emergency($exception->getMessage(),
                array_merge($request->only("username"), [$exception]));
            return $this->sendError(__("errors.error"),
                $request->only("username"), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $request->safe()->merge(["status" => Status::LOCKED]);
            return $this->sendResponse(__('register.registered'),
                ["user" => UserResource::collection($this->authenticate->register(UserData::fromRequest($request)))],
                Response::HTTP_CREATED);
        } catch (ModelNotFoundException $modelNotFoundException) {
            Log::stack(["stack"])->warning($modelNotFoundException->getMessage(), [$modelNotFoundException]);
            return $this->sendError(__("register.create"),
                $request->only("name", "email"),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Throwable $exception) {
            Log::stack(["stack"])->warning($exception->getMessage(), [$exception]);
            return $this->sendError(__("errors.error"),
                $request->only("name", "email"),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @param ForgotPasswordRequest $request
     * @return JsonResponse
     */
    public function forgot(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            return $this->response($this->authenticate->forgot($request->input("email")));
        } catch (Throwable $exception) {
            Log::stack(["stack"])->warning($exception->getMessage(), [$exception]);
            return $this->sendError(__("errors.error"),
                $request->only("name", "email"),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**dale
     * @param PasswordResetRequest $request
     * @return JsonResponse
     */
    public function reset(PasswordResetRequest $request): JsonResponse
    {
        try {
            $user = UserData::fromRequest($request)->additional($request->only("password_confirmation", "token"));
            return $this->response($this->authenticate->reset($user));
        } catch (Throwable $exception) {
            Log::stack(["stack"])->warning($exception->getMessage(), [$exception]);
            return $this->sendError(__("errors.error"),
                $request->only("name", "email"),
                Response::HTTP_INTERNAL_SERVER_ERROR);
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
     * @param array $data
     * @return JsonResponse
     */
    private function response(array $data): JsonResponse
    {
        return Arr::has($data, "status") ? $this->sendResponse("", $data) : $this->sendError("", $data);
    }
}
