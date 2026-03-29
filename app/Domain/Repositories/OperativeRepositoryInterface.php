<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\OperativeEntity;

interface OperativeRepositoryInterface
{
    /**
     * @return list<OperativeEntity>
     */
    public function all(): array;
    public function findById(string $id): ?OperativeEntity;
}
