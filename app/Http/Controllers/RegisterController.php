<?php

namespace App\Http\Controllers;

use App\Factory\Auth\IApi;
use App\Http\Builder\Auth\UserData;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\ResponseWithHttpStatus;
use App\UseCase\Status;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class RegisterController extends Controller
{
    use ResponseWithHttpStatus;

    /**
     * @param IApi $api
     */
    public function __construct(private IApi $api)
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
            return $this->success(__('register.registered'),
                ["user" => new UserResource($this->api->register($this->getUser($request), "tags"))],
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
     * @return UserData
     */
    private function getUser(Request $request): UserData
    {
        $builder = new UserData();
        $builder->name = $request->input("name");
        $builder->email = $request->input("email");
        $builder->password = $request->input("password");
        $builder->status = Status::LOCKED;
        return $builder->build();
    }
}
