<?php

declare(strict_types=1);

namespace App\Application\DTO\Order;

use App\Domain\Entities\OrderAssignmentEntity;
use App\Domain\Entities\OrderEntity;
use App\Domain\Entities\OrderItemEntity;

final class OrderPayloadDto
{
    /**
     * @param list<OrderItemEntity> $items
     * @param list<OrderAssignmentEntity> $assignments
     */
    public function __construct(
        private OrderEntity $order,
        private array $items,
        private array $assignments,
    ) {
    }

    public function getOrder(): OrderEntity
    {
        return $this->order;
    }

    /**
     * @return list<OrderItemEntity>
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return list<OrderAssignmentEntity>
     */
    public function getAssignments(): array
    {
        return $this->assignments;
    }
}
