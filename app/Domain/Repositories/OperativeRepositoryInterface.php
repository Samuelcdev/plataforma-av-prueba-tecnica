<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\OperativeEntity;

interface OperativeRepositoryInterface
{
    /**
     * @return list<OperativeEntity>
     */
    public function all(array $filters = []): array;

    public function count(array $filters = []): int;

    public function findById(string $id): ?OperativeEntity;
}
