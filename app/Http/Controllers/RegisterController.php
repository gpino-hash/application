<?php

namespace App\Http\Controllers;

use App\Factory\Auth\IAbstractAuthFactory;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\ResponseWithHttpStatus;
use App\Http\Traits\TryAccess;
use App\UseCase\Status;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class RegisterController extends Controller
{
    use ResponseWithHttpStatus, TryAccess;

    public function __construct(private IAbstractAuthFactory $authFactory)
    {
        $this->middleware('guest');
    }

    /**
     * @param RegisterRequest $request
     * @return Response|Application|ResponseFactory
     */
    public function register(RegisterRequest $request): Response|Application|ResponseFactory
    {
        try {
            $request->merge(["status" => Status::LOCKED]);
            return $this->success(__('register.registered'),
                ["user" => new UserResource($this->create($request))],
                Response::HTTP_CREATED);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return $this->failure(__("register.create"),
                $request->only("name", "email"),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Throwable $exception) {
            return $this->failure(__("errors.error"),
                $request->only("name", "email"),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function create(Request $request): mixed
    {
        return $this->tryAccessAuth()->register($request->only("name", "email", "password", "status"));
    }
}
