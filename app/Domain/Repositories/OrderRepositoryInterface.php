<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\OrderEntity;

interface OrderRepositoryInterface
{
    /**
     * @return list<OrderEntity>
     */
    public function all(): array;
    /**
     * @return list<OrderEntity>
     */
    public function findByHotelId(string $hotelId): array;
    public function findById(string $id): ?OrderEntity;
    public function save(OrderEntity $order): OrderEntity;
    public function deleteById(string $id): bool;
}
