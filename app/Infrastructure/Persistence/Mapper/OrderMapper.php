<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Mapper;

use App\Domain\Entities\OrderEntity;
use App\Infrastructure\Persistence\Models\Order;
use DateTimeImmutable;
use DateTimeInterface;
use Illuminate\Support\Collection;

final class OrderMapper
{
    public function toEntity(Order $order): OrderEntity
    {
        $startDate = $this->toImmutable($order->start_date);
        $endDate = $this->toImmutable($order->end_date);

        return new OrderEntity(
            (string) $order->id,
            (string) $order->hotel_id,
            (string) $order->name,
            (string) $order->service_type,
            $startDate ?? new DateTimeImmutable((string) $order->start_date),
            $endDate ?? new DateTimeImmutable((string) $order->end_date),
            $this->toImmutable($order->created_at),
            $this->toImmutable($order->updated_at),
            (string) ($order->status ?? 'active'),
        );
    }

    /**
     * @return array<OrderEntity>
     */
    public function toCollectionEntity(Collection|array $items): array
    {
        $models = $items instanceof Collection ? $items->all() : $items;

        return array_map(
            fn (mixed $item): OrderEntity => $this->toEntity($item),
            $models
        );
    }

    private function toImmutable(mixed $value): ?DateTimeImmutable
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof DateTimeImmutable) {
            return $value;
        }

        if ($value instanceof DateTimeInterface) {
            return DateTimeImmutable::createFromInterface($value);
        }

        return new DateTimeImmutable((string) $value);
    }
}
