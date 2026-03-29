<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\OrderAssignmentEntity;

interface OrderAssignmentRepositoryInterface
{
    /**
     * @return list<OrderAssignmentEntity>
     */
    public function all(): array;
    public function findById(string $id): ?OrderAssignmentEntity;
    public function save(OrderAssignmentEntity $orderAssignment): OrderAssignmentEntity;
    public function deleteById(string $id): bool;
}
