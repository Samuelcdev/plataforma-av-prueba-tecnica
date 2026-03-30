<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Mapper;

use App\Domain\Entities\OrderAssignmentEntity;
use App\Infrastructure\Persistence\Models\OrderAssignment;
use DateTimeImmutable;
use DateTimeInterface;
use Illuminate\Support\Collection;

final class OrderAssignmentMapper
{
    public function toEntity(OrderAssignment $orderAssignment): OrderAssignmentEntity
    {
        return new OrderAssignmentEntity(
            (string) $orderAssignment->id,
            (string) $orderAssignment->order_id,
            (string) $orderAssignment->operative_id,
            (string) $orderAssignment->admin_id,
            $this->toImmutable($orderAssignment->assigned_at),
            null,
        );
    }

    /**
     * @return array<OrderAssignmentEntity>
     */
    public function toCollectionEntity(Collection|array $items): array
    {
        $models = $items instanceof Collection ? $items->all() : $items;

        return array_map(
            fn (mixed $item): OrderAssignmentEntity => $this->toEntity($item),
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
