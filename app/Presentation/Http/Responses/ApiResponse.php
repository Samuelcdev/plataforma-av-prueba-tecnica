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
        return response()->json([
            'success' => true,
            'message' => $message,
            'total' => $total ?? self::resolveTotal($data),
            'data' => $data,
        ], $status);
    }

    public static function error(
        string $message,
        int $status,
        mixed $data = null,
        int $total = 0,
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'total' => $total,
            'data' => $data,
        ], $status);
    }

    private static function resolveTotal(mixed $data): int
    {
        if ($data === null) {
            return 0;
        }

        if (is_array($data) || $data instanceof Countable) {
            return count($data);
        }

        return 1;
    }
}
