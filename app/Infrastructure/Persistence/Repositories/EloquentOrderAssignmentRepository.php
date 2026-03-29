<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\OrderAssignmentEntity;
use App\Domain\Repositories\OrderAssignmentRepositoryInterface;
use App\Infrastructure\Persistence\Mapper\OrderAssignmentMapper;
use App\Infrastructure\Persistence\Models\OrderAssignment;
use DateTimeImmutable;

final class EloquentOrderAssignmentRepository implements OrderAssignmentRepositoryInterface
{
    public function __construct(private OrderAssignmentMapper $mapper)
    {
    }

    public function all(): array
    {
        return $this->mapper->toCollectionEntity(
            OrderAssignment::query()->orderBy('assigned_at')->get()
        );
    }

    public function findById(string $id): ?OrderAssignmentEntity
    {
        $orderAssignment = OrderAssignment::query()->find($id);

        return $orderAssignment ? $this->mapper->toEntity($orderAssignment) : null;
    }

    public function save(OrderAssignmentEntity $orderAssignmentEntity): OrderAssignmentEntity
    {
        $orderAssignment = OrderAssignment::query()->find($orderAssignmentEntity->getId()) ?? new OrderAssignment();

        $orderAssignment->id = $orderAssignmentEntity->getId();
        $orderAssignment->order_id = $orderAssignmentEntity->getOrderId();
        $orderAssignment->operative_id = $orderAssignmentEntity->getOperativeId();
        $orderAssignment->admin_id = $orderAssignmentEntity->getAdminId();

        if ($orderAssignmentEntity->getAssignedAt() !== null) {
            $orderAssignment->assigned_at = $this->toDatabaseDateTime($orderAssignmentEntity->getAssignedAt());
        }

        $orderAssignment->save();

        return $this->mapper->toEntity($orderAssignment->fresh() ?? $orderAssignment);
    }

    public function deleteById(string $id): bool
    {
        $orderAssignment = OrderAssignment::query()->find($id);

        return $orderAssignment ? (bool) $orderAssignment->delete() : false;
    }
    private function toDatabaseDateTime(?DateTimeImmutable $value): ?string
    {
        return $value?->format('Y-m-d H:i:s');
    }
}
