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
    /**
     * @return list<OrderAssignmentEntity>
     */
    public function findByOrderId(string $orderId): array;
    public function findById(string $id): ?OrderAssignmentEntity;
    public function findByOrderAndOperativeId(string $orderId, string $operativeId): ?OrderAssignmentEntity;
    /**
     * @return array<int, array{order_id:string, start_date:string, end_date:string}>
     */
    public function findAssignmentsWithOrderWindow(string $operativeId): array;
    public function save(OrderAssignmentEntity $orderAssignment): OrderAssignmentEntity;
    public function deleteById(string $id): bool;
}
