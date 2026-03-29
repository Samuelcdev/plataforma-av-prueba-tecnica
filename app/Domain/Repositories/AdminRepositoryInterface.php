<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\AdminEntity;

interface AdminRepositoryInterface
{
    /**
     * @return list<AdminEntity>
     */
    public function all(): array;
    public function findById(string $id): ?AdminEntity;
    public function findByUserId(string $userId): ?AdminEntity;
}
