<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\OrderEntity;
use App\Domain\Repositories\OrderRepositoryInterface;
use App\Infrastructure\Mapper\OrderMapper;
use App\Models\Order;
use DateTimeImmutable;

final class EloquentOrderRepository implements OrderRepositoryInterface
{
    public function __construct(private OrderMapper $mapper)
    {
    }

    public function all(): array
    {
        return $this->mapper->toCollectionEntity(
            Order::query()->orderBy('start_date')->get()
        );
    }

    public function findById(string $id): ?OrderEntity
    {
        $order = Order::query()->find($id);

        return $order ? $this->mapper->toEntity($order) : null;
    }

    public function save(OrderEntity $orderEntity): OrderEntity
    {
        $order = Order::query()->find($orderEntity->getId()) ?? new Order();

        $order->id = $orderEntity->getId();
        $order->hotel_id = $orderEntity->getHotelId();
        $order->name = $orderEntity->getName();
        $order->service_type = $orderEntity->getServiceType();
        $order->start_date = $this->toDatabaseDateTime($orderEntity->getStartDate());
        $order->end_date = $this->toDatabaseDateTime($orderEntity->getEndDate());

        if ($orderEntity->getCreatedAt() !== null) {
            $order->created_at = $this->toDatabaseDateTime($orderEntity->getCreatedAt());
        }

        if ($orderEntity->getUpdatedAt() !== null) {
            $order->updated_at = $this->toDatabaseDateTime($orderEntity->getUpdatedAt());
        }

        $order->save();

        return $this->mapper->toEntity($order->fresh() ?? $order);
    }

    public function deleteById(string $id): bool
    {
        $order = Order::query()->find($id);

        return $order ? (bool) $order->delete() : false;
    }
    private function toDatabaseDateTime(?DateTimeImmutable $value): ?string
    {
        return $value?->format('Y-m-d H:i:s');
    }
}
