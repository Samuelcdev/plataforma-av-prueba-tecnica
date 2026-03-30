<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers;

use App\Application\DTO\Order\AssignOrderOperativesDto;
use App\Application\DTO\Order\CancelOrderDto;
use App\Application\DTO\Order\CreateOrderDto;
use App\Application\DTO\Order\GetOrdersDto;
use App\Application\DTO\Order\OrderItemInputDto;
use App\Application\DTO\Order\UpdateOrderDto;
use App\Application\Services\OrderService;
use App\Presentation\Http\Requests\AssignOrderOperativesRequest;
use App\Presentation\Http\Requests\CancelOrderRequest;
use App\Presentation\Http\Requests\GetOrdersRequest;
use App\Presentation\Http\Requests\StoreOrderRequest;
use App\Presentation\Http\Requests\UpdateOrderRequest;
use App\Presentation\Http\Resources\OrderResource;
use App\Presentation\Http\Responses\ApiResponse;
use DateTimeImmutable;
use Illuminate\Http\JsonResponse;

final class OrderController extends Controller
{
    public function __construct(private OrderService $orderService)
    {
    }

    public function index(GetOrdersRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $startFrom = isset($payload['start_from'])
            ? (new DateTimeImmutable((string) $payload['start_from']))->format('Y-m-d H:i:s')
            : null;
        $dto = new GetOrdersDto(
            search: isset($payload['search']) ? trim((string) $payload['search']) : null,
            page: isset($payload['page']) ? (int) $payload['page'] : 1,
            total: isset($payload['total']) ? (int) $payload['total'] : 10,
            sort: isset($payload['sort']) ? (string) $payload['sort'] : 'start_date',
            order: isset($payload['order']) ? (string) $payload['order'] : 'asc',
            startFrom: $startFrom,
            status: isset($payload['status']) ? trim((string) $payload['status']) : null,
            date: isset($payload['date']) ? trim((string) $payload['date']) : null,
        );
        $result = $this->orderService->index($dto);

        return ApiResponse::success(
            data: array_map(
                static fn ($order): array => OrderResource::toArray($order),
                $result['orders'],
            ),
            total: $result['total'],
            message: 'Orders retrieved',
            status: 200,
        );
    }

    public function show(string $id): JsonResponse
    {
        $order = $this->orderService->show($id);

        return ApiResponse::success(
            data: OrderResource::toArray($order),
            message: 'Order retrieved',
            status: 200,
        );
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $dto = new CreateOrderDto(
            name: (string) $payload['name'],
            serviceType: (string) $payload['service_type'],
            startDate: new DateTimeImmutable((string) $payload['start_date']),
            endDate: new DateTimeImmutable((string) $payload['end_date']),
            items: $this->mapItems($payload['items'] ?? []),
        );

        $order = $this->orderService->store($dto);

        return ApiResponse::success(
            data: OrderResource::toArray($order),
            message: 'Order created',
            status: 201,
        );
    }

    public function update(UpdateOrderRequest $request, string $id): JsonResponse
    {
        $payload = $request->validated();

        $dto = new UpdateOrderDto(
            id: $id,
            name: (string) $payload['name'],
            serviceType: (string) $payload['service_type'],
            startDate: new DateTimeImmutable((string) $payload['start_date']),
            endDate: new DateTimeImmutable((string) $payload['end_date']),
            items: $this->mapItems($payload['items'] ?? []),
        );

        $order = $this->orderService->update($dto);

        return ApiResponse::success(
            data: OrderResource::toArray($order),
            message: 'Order updated',
            status: 200,
        );
    }

    public function assignOperatives(AssignOrderOperativesRequest $request, string $id): JsonResponse
    {
        $payload = $request->validated();

        $dto = new AssignOrderOperativesDto(
            orderId: $id,
            operativeIds: array_values(array_map('strval', $payload['operative_ids'] ?? [])),
        );

        $order = $this->orderService->assignOperatives($dto);

        return ApiResponse::success(
            data: OrderResource::toArray($order),
            message: 'Operatives assigned',
            status: 200,
        );
    }

    public function cancel(CancelOrderRequest $request, string $id): JsonResponse
    {
        $order = $this->orderService->cancel(new CancelOrderDto($id));

        return ApiResponse::success(
            data: OrderResource::toArray($order),
            message: 'Order cancelled',
            status: 200,
        );
    }

    /**
     * @param mixed $rawItems
     * @return list<OrderItemInputDto>
     */
    private function mapItems(mixed $rawItems): array
    {
        if (! is_array($rawItems)) {
            return [];
        }

        return array_map(
            static fn (array $item): OrderItemInputDto => new OrderItemInputDto(
                itemId: (string) ($item['item_id'] ?? ''),
                quantity: (int) ($item['quantity'] ?? 0),
            ),
            $rawItems,
        );
    }
}
