<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers;

use App\Application\DTO\Operative\GetOperativesDto;
use App\Application\Services\OperativeService;
use App\Presentation\Http\Requests\GetOperativesRequest;
use App\Presentation\Http\Resources\OperativeResource;
use App\Presentation\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

final class OperativeController extends Controller
{
    public function __construct(private OperativeService $operativeService)
    {
    }

    public function get(GetOperativesRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $dto = new GetOperativesDto(
            search: isset($payload['search']) ? trim((string) $payload['search']) : null,
            page: isset($payload['page']) ? (int) $payload['page'] : 1,
            total: isset($payload['total']) ? (int) $payload['total'] : 10,
            isActive: array_key_exists('is_active', $payload)
                ? filter_var($payload['is_active'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
                : true,
        );

        $result = $this->operativeService->get($dto);

        return ApiResponse::success(
            data: array_map(
                static fn ($operative): array => OperativeResource::toArray($operative),
                $result['operatives'],
            ),
            total: $result['total'],
            message: 'Operatives retrieved',
            status: 200,
        );
    }
}
