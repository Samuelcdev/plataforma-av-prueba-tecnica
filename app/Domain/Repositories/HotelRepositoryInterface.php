<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\HotelEntity;

interface HotelRepositoryInterface
{
    /**
     * @return list<HotelEntity>
     */
    public function all(array $filters = []): array;
    public function count(array $filters = []): int;

    public function findById(string $id): ?HotelEntity;

    public function findByUserId(string $userId): ?HotelEntity;

    public function findByNit(string $nit): ?HotelEntity;

    public function save(HotelEntity $hotel): HotelEntity;

    public function deleteById(string $id): bool;
}
