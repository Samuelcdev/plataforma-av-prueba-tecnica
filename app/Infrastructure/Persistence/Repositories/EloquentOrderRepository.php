<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\OrderEntity;
use App\Domain\Repositories\OrderRepositoryInterface;
use App\Infrastructure\Persistence\Mapper\OrderMapper;
use App\Infrastructure\Persistence\Models\Order;
use DateTimeImmutable;

final class EloquentOrderRepository implements OrderRepositoryInterface
{
    public function __construct(private OrderMapper $mapper)
    {
    }

    public function all(array $filters = []): array
    {
        $query = Order::query();
        $this->applyFilters($query, $filters);

        $total = (int) ($filters['total'] ?? 10);
        $page = (int) ($filters['page'] ?? 1);
        $total = max(1, min($total, 100));
        $page = max(1, $page);

        return $this->mapper->toCollectionEntity(
            $query
                ->orderBy('start_date')
                ->offset(($page - 1) * $total)
                ->limit($total)
                ->get()
        );
    }

    public function findByHotelId(string $hotelId, array $filters = []): array
    {
        $query = Order::query()->where('hotel_id', $hotelId);
        $this->applyFilters($query, $filters);

        $total = (int) ($filters['total'] ?? 10);
        $page = (int) ($filters['page'] ?? 1);
        $total = max(1, min($total, 100));
        $page = max(1, $page);

        return $this->mapper->toCollectionEntity(
            $query
                ->orderBy('start_date')
                ->offset(($page - 1) * $total)
                ->limit($total)
                ->get()
        );
    }

    public function count(array $filters = []): int
    {
        $query = Order::query();
        $this->applyFilters($query, $filters);

        return (int) $query->count();
    }

    public function countByHotelId(string $hotelId, array $filters = []): int
    {
        $query = Order::query()->where('hotel_id', $hotelId);
        $this->applyFilters($query, $filters);

        return (int) $query->count();
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
        $order->status = $orderEntity->getStatus();

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

    private function applyFilters(\Illuminate\Database\Eloquent\Builder $query, array $filters): void
    {
        $search = trim((string) ($filters['search'] ?? ''));
        if ($search === '') {
            return;
        }

        $query->where(function ($builder) use ($search): void {
            $builder
                ->where('name', 'like', '%' . $search . '%')
                ->orWhere('service_type', 'like', '%' . $search . '%');
        });
    }

    private function toDatabaseDateTime(?DateTimeImmutable $value): ?string
    {
        return $value?->format('Y-m-d H:i:s');
    }
}
