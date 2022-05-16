<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\UserData;
use App\Events\CreateUserInformation;
use App\Events\Registered;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Pagination\UserPagination;
use App\UseCase\Auth\IAuthenticate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class UserController extends Controller
{

    public function __construct(private UserPagination $userPagination, private IAuthenticate $authenticate)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            return $this->sendResponse($this->userPagination->paginate(), __("pagination.success"));
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
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $user = $this->authenticate->register(UserData::fromRequest($request));
            CreateUserInformation::dispatch($user);
            Registered::dispatch($user);
            return $this->sendResponse(new UserResource($user), "");
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
            return $this->sendResponse(new UserResource($user), "");
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
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $before = $user->toArray();
        $user->update($request->only("name", "status"));
        $user->userInformation()->update($request->only("firstname", "lastname", "birthdate"));

        $user->refresh();
        if (!empty(array_diff_assoc($before, $user->toArray()))) {

        }

        return $this->sendResponse(new UserResource($user), "");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return Response
     */
    public function destroy(User $user)
    {
        //
    }

}
