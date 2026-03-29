<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\ItemEntity;

interface ItemRepositoryInterface
{
    /**
     * @return list<ItemEntity>
     */
    public function all(): array;

    public function findById(string $id): ?ItemEntity;

    public function save(ItemEntity $item): ItemEntity;

    public function deleteById(string $id): bool;
}
