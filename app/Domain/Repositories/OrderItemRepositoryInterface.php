<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\OrderItemEntity;

interface OrderItemRepositoryInterface
{
    /**
     * @return list<OrderItemEntity>
     */
    public function all(): array;
    public function findById(string $id): ?OrderItemEntity;
    public function save(OrderItemEntity $orderItem): OrderItemEntity;
    public function deleteById(string $id): bool;
}
