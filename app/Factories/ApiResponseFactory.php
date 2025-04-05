<?php

namespace App\Factories;

use Illuminate\Http\JsonResponse;

class ApiResponseFactory
{
    /**
     * Create a success response with data.
     *
     * @param mixed $data
     * @param int $status
     * @return JsonResponse
     */
    public static function success($data, int $status = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
        ], $status);
    }

    /**
     * Create an error response with a message.
     *
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    public static function error(string $message, int $status = 400): JsonResponse
    {
        return response()->json([
            'message' => $message,
        ], $status);
    }
}
