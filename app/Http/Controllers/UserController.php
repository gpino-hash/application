<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\UserData;
use App\Enums\Status;
use App\Events\CreateUserInformation;
use App\Events\Registered;
use App\Events\UpdateUserInformation;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Pagination\UserPagination;
use App\UseCase\Auth\IAuthenticate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{

    public function __construct(private IAuthenticate $authenticate)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $this->middleware(["auth:sanctum", 'abilities:' . implode(",", config("permission.all"))]);
        try {
            return $this->sendResponse($this->paginate(resolve(UserPagination::class)),
                __("pagination.success"));
        } catch (Throwable $exception) {
            Log::stack(["stack"])->warning($exception->getMessage(), [$exception]);
            return $this->sendError(__("errors.error"),
                $this->isProduction() ? [] : [$exception->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(UserRequest $request): JsonResponse
    {
        $this->middleware(["auth:sanctum", 'abilities:' . implode(",", config("permission.create"))]);
        try {
            $user = $this->authenticate->register(UserData::fromRequest($request));
            CreateUserInformation::dispatch($user, $request->only("firstname", "lastname", "birthdate", "id_number"));
            if (Status::ACTIVE !== $request->input("status")) {
                Registered::dispatch($user);
            }
            return $this->sendResponse(new UserResource($user));
        } catch (Throwable $exception) {
            Log::stack(["stack"])->warning($exception->getMessage(), [$exception]);
            return $this->sendError(__("errors.error"),
                $this->isProduction() ? [] : [$exception->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        try {
            return $this->sendResponse(new UserResource($user));
        } catch (Throwable $exception) {
            Log::stack(["stack"])->warning($exception->getMessage(), [$exception]);
            return $this->sendError(__("errors.error"),
                $this->isProduction() ? [] : [$exception->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UserRequest $request, User $user): JsonResponse
    {
        $this->middleware(["auth:sanctum", 'abilities:' . implode(",", config("permission.update"))]);
        try {
            $user->update($request->only("password", "status"));
            UpdateUserInformation::dispatch($user, $request->all());
            $user->refresh();
            return $this->sendResponse([
                "user" => new UserResource($user),
            ], __("response.update"));
        } catch (Throwable $exception) {
            Log::stack(["stack"])->warning($exception->getMessage(), [$exception]);
            return $this->sendError(__("errors.error"),
                $this->isProduction() ? [] : [$exception->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user)
    {
        $this->middleware(["auth:sanctum", 'abilities:' . implode(",", config("permission.update"))]);
        try {
            $user->deleteOrFail();
            return $this->sendResponse();
        } catch (Throwable $exception) {
            Log::stack(["stack"])->warning($exception->getMessage(), [$exception]);
            return $this->sendError(__("errors.error"),
                $this->isProduction() ? [] : [$exception->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
