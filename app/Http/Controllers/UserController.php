<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Pagination\UserPagination;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function __construct(private UserPagination $userPagination)
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
        } catch (\Throwable $exception) {
            Log::stack(["stack"])->warning($exception->getMessage(), [$exception]);
            return $this->sendError(__("errors.error"),
                $exception->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\User $user
     * @return Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @return Response
     */
    public function destroy(User $user)
    {
        //
    }

}
