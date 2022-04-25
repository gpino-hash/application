<?php

namespace App\Http\Controllers;

use App\Factory\Auth\IAbstractAuthFactory;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Traits\ResponseWithHttpStatus;
use App\Http\Traits\TryAccess;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class PasswordController extends Controller
{
    use ResponseWithHttpStatus, TryAccess;

    public function __construct(private IAbstractAuthFactory $authFactory)
    {
        $this->middleware("guest");
    }

    /**
     * @param ForgotPasswordRequest $request
     * @return Response|Application|ResponseFactory
     */
    public function forgot(ForgotPasswordRequest $request): Response|Application|ResponseFactory
    {
        return $this->response($this->tryAccessAuth()->forgot($request->input("email")));
    }

    /**
     * @param PasswordResetRequest $request
     * @return Response|Application|ResponseFactory
     */
    public function reset(PasswordResetRequest $request): Response|Application|ResponseFactory
    {
        return $this->response($this->tryAccessAuth()->reset($request->only("email", "password", "password_confirmation", "token")));
    }

    /**
     * @param array $data
     * @return Response|Application|ResponseFactory
     */
    private function response(array $data): Response|Application|ResponseFactory
    {
        return Arr::has($data, "status") ? $this->success("", $data) : $this->failure("", $data);
    }
}
