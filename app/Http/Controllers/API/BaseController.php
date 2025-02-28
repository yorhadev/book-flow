<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\BaseResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class BaseController extends Controller
{
    public function sendBaseResponse(
        string $message,
        int $status = Response::HTTP_OK
    ) {
        $data = [];

        $baseResponse = new BaseResource($data, $message);

        return $baseResponse->response()->setStatusCode($status);
    }

    public function sendThrowable(
        string $message,
        \Throwable $exception,
        int $status = Response::HTTP_INTERNAL_SERVER_ERROR
    ): JsonResponse {
        $data = [];

        $errors = ['exception' => $exception->getMessage()];

        $errorResponse = new BaseResource($data, $message, $errors);

        return $errorResponse->response()->setStatusCode($status);
    }

    public function sendValidationException(
        string $message,
        ValidationException $exception,
        int $status = Response::HTTP_UNPROCESSABLE_ENTITY
    ): JsonResponse {
        $data = [];

        $errors = $exception->errors();

        $errorResponse = new BaseResource($data, $message, $errors);

        return $errorResponse->response()->setStatusCode($status);
    }
}
