<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\RoleEntity;

interface RoleRepositoryInterface
{
    /**
     * @return list<RoleEntity>
     */
    public function all(): array;
    public function findById(int $id): ?RoleEntity;
}
