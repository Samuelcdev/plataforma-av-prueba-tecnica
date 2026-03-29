<?php

declare(strict_types=1);

namespace App\Infrastructure\Mapper;

use App\Domain\Entities\OrderItemEntity;
use App\Models\OrderItem;
use Illuminate\Support\Collection;

final class OrderItemMapper
{
    public function toEntity(OrderItem $orderItem): OrderItemEntity
    {
        return new OrderItemEntity(
            (string) $orderItem->id,
            (string) $orderItem->order_id,
            (string) $orderItem->item_id,
            (int) $orderItem->quantity,
        );
    }

    /**
     * @return array<OrderItemEntity>
     */
    public function toCollectionEntity(Collection|array $items): array
    {
        $models = $items instanceof Collection ? $items->all() : $items;

        return array_map(
            fn (mixed $item): OrderItemEntity => $this->toEntity($item),
            $models
        );
    }
}
