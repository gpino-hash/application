<?php

namespace App\Http\Controllers;

use App\Factory\Auth\GuardName;
use App\Factory\Auth\IApi;
use App\Http\Builder\Auth\UserBuilder;
use App\Http\Data\Auth\UserData;
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

    private IApi $api;

    /**
     * @param IApi $api
     */
    public function __construct(IApi $api)
    {
        $this->middleware('guest');
        $this->api = $api;
    }

    /**
     * @param RegisterRequest $request
     * @return Response|Application|ResponseFactory
     */
    public function register(RegisterRequest $request): Response|Application|ResponseFactory
    {
        try {
            return $this->success("Your data was saved correctly.",
                ["user" => new UserResource($this->api->register(GuardName::WEB, $this->getUser($request)))]);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return $this->failure("We are having difficulty saving your data. Please try again later.", Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Throwable $exception) {
            return $this->failure("We are currently having difficulties, please try again later.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $request
     * @return UserData
     */
    private function getUser(Request $request): UserData
    {
        return UserBuilder::builder()
            ->name($request->input("name"))
            ->email($request->input("email"))
            ->password($request->input("password"))
            ->status(Status::LOCKED)
            ->build();
    }
}
