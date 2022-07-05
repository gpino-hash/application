<?php

namespace App\Http\Controllers;

use App\Pagination\Creator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * success response method.
     *
     * @param $result
     * @param $message
     * @param $code
     * @return JsonResponse
     */
    public function sendResponse($result = null, $message = null, $code = Response::HTTP_OK): JsonResponse
    {
        if (empty($message)) {
            $message = __('response.success');
        }
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if (!empty($result) && !$result instanceof ResourceCollection) {
            $response['data'] = $result;
        } else {
            $response = array_merge($response, collect($result)->toArray());
        }

        return response()->json($response, $code);
    }

    /**
     * return error response.
     *
     * @param $error
     * @param array $errorMessages
     * @param int $code
     * @return JsonResponse
     */
    public function sendError($error, $errorMessages = [], $code = Response::HTTP_NOT_FOUND): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['error'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    /**
     * @return bool
     */
    public function isProduction(): bool
    {
        return env("APP_ENV") === "prod";
    }

    /**
     * @param Creator $creator
     * @return mixed
     */
    public function paginate(Creator $creator): mixed
    {
        return $creator->paginate();
    }
}
