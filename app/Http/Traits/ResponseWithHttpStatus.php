<?php

namespace App\Http\Traits;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

trait ResponseWithHttpStatus
{
    /**
     * @param $message
     * @param array $data
     * @param int $status
     * @return Application|ResponseFactory|Response
     */
    protected function success($message, array $data = [], int $status = 200): Response|Application|ResponseFactory
    {
        return response(['success' => true, 'message' => $message, 'data' => $data,], $status);
    }

    /**
     * @param $message
     * @param int $status
     * @return Application|ResponseFactory|Response
     */
    protected function failure($message, int $status = Response::HTTP_UNPROCESSABLE_ENTITY): Response|Application|ResponseFactory
    {
        return response(['success' => false, 'message' => $message,], $status);
    }
}
