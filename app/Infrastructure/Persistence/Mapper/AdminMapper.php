<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Mapper;

use App\Domain\Entities\AdminEntity;
use App\Infrastructure\Persistence\Models\Admin;
use Illuminate\Support\Collection;

final class AdminMapper
{
    public function toEntity(Admin $admin): AdminEntity
    {
        return new AdminEntity(
            (string) $admin->id,
            (string) $admin->user_id,
            (string) $admin->document_type,
            (string) $admin->document,
            (string) $admin->name,
            $admin->email !== null ? (string) $admin->email : null,
            $admin->phone !== null ? (string) $admin->phone : null,
        );
    }

    /**
     * @return array<AdminEntity>
     */
    public function toCollectionEntity(Collection|array $items): array
    {
        $models = $items instanceof Collection ? $items->all() : $items;

        return array_map(
            fn (mixed $item): AdminEntity => $this->toEntity($item),
            $models
        );
    }
}
