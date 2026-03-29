<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\OrderItemEntity;
use App\Domain\Repositories\OrderItemRepositoryInterface;
use App\Infrastructure\Mapper\OrderItemMapper;
use App\Models\OrderItem;

final class EloquentOrderItemRepository implements OrderItemRepositoryInterface
{
    public function __construct(private OrderItemMapper $mapper)
    {
    }

    public function all(): array
    {
        return $this->mapper->toCollectionEntity(
            OrderItem::query()->orderBy('order_id')->get()
        );
    }

    public function findById(string $id): ?OrderItemEntity
    {
        $orderItem = OrderItem::query()->find($id);

        return $orderItem ? $this->mapper->toEntity($orderItem) : null;
    }

    public function save(OrderItemEntity $orderItemEntity): OrderItemEntity
    {
        $orderItem = OrderItem::query()->find($orderItemEntity->getId()) ?? new OrderItem();

        $orderItem->id = $orderItemEntity->getId();
        $orderItem->order_id = $orderItemEntity->getOrderId();
        $orderItem->item_id = $orderItemEntity->getItemId();
        $orderItem->quantity = $orderItemEntity->getQuantity();

        $orderItem->save();

        return $this->mapper->toEntity($orderItem->fresh() ?? $orderItem);
    }

    public function deleteById(string $id): bool
    {
        $orderItem = OrderItem::query()->find($id);

        return $orderItem ? (bool) $orderItem->delete() : false;
    }
}
