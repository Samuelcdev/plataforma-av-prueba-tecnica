<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers;

use App\Application\DTO\Item\GetItemsDto;
use App\Application\Services\ItemService;
use App\Presentation\Http\Requests\GetItemsRequest;
use App\Presentation\Http\Resources\ItemResource;
use App\Presentation\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

final class ItemController extends Controller
{
    public function __construct(private ItemService $itemService)
    {
    }

    public function get(GetItemsRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $dto = new GetItemsDto(
            search: isset($payload['search']) ? trim((string) $payload['search']) : null,
            page: isset($payload['page']) ? (int) $payload['page'] : 1,
            total: isset($payload['total']) ? (int) $payload['total'] : 10,
            isActive: array_key_exists('is_active', $payload)
                ? filter_var($payload['is_active'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
                : true,
        );

        $result = $this->itemService->get($dto);

        return ApiResponse::success(
            data: array_map(
                static fn ($item): array => ItemResource::toArray($item),
                $result['items'],
            ),
            total: $result['total'],
            message: 'Items retrieved',
            status: 200,
        );
    }
}
