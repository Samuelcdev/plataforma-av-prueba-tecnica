<?php

declare(strict_types=1);

namespace App\Presentation\Http\Responses;

use Countable;
use Illuminate\Http\JsonResponse;

final class ApiResponse
{
    public static function success(
        mixed $data = null,
        string $message = 'OK',
        int $status = 200,
        ?int $total = null,
    ): JsonResponse {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if (isset($total)) $response['total'] = $total;
        if (isset($data)) $response['data'] = $data;

        return response()->json($response, $status);
    }

    public static function error(
        string $message,
        int $status,
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }
}
