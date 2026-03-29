<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Mapper;

use App\Domain\Entities\RoleEntity;
use App\Infrastructure\Persistence\Models\Role;
use DateTimeImmutable;
use DateTimeInterface;
use Illuminate\Support\Collection;

final class RoleMapper
{
    public function toEntity(Role $role): RoleEntity
    {
        return new RoleEntity(
            (int) $role->id,
            (string) $role->name,
            $this->toImmutable($role->created_at),
        );
    }

    /**
     * @return array<RoleEntity>
     */
    public function toCollectionEntity(Collection|array $items): array
    {
        $models = $items instanceof Collection ? $items->all() : $items;

        return array_map(
            fn (mixed $item): RoleEntity => $this->toEntity($item),
            $models
        );
    }

    private function toImmutable(mixed $value): ?DateTimeImmutable
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof DateTimeImmutable) {
            return $value;
        }

        if ($value instanceof DateTimeInterface) {
            return DateTimeImmutable::createFromInterface($value);
        }

        return new DateTimeImmutable((string) $value);
    }
}
