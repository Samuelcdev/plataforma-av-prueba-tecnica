<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\RoleEntity;
use App\Domain\Repositories\RoleRepositoryInterface;
use App\Infrastructure\Mapper\RoleMapper;
use App\Models\Role;
use DateTimeImmutable;

final class EloquentRoleRepository implements RoleRepositoryInterface
{
    public function __construct(private RoleMapper $mapper)
    {
    }

    public function all(): array
    {
        return $this->mapper->toCollectionEntity(
            Role::query()->orderBy('id')->get()
        );
    }

    public function findById(int $id): ?RoleEntity
    {
        $role = Role::query()->find($id);

        return $role ? $this->mapper->toEntity($role) : null;
    }

    public function save(RoleEntity $roleEntity): RoleEntity
    {
        $role = Role::query()->find($roleEntity->getId()) ?? new Role();

        $role->id = $roleEntity->getId();
        $role->name = $roleEntity->getName();
        $role->created_at = $this->toDatabaseDateTime($roleEntity->getCreatedAt());

        $role->save();

        return $this->mapper->toEntity($role->fresh() ?? $role);
    }

    public function deleteById(int $id): bool
    {
        $role = Role::query()->find($id);

        return $role ? (bool) $role->delete() : false;
    }
    private function toDatabaseDateTime(?DateTimeImmutable $value): ?string
    {
        return $value?->format('Y-m-d H:i:s');
    }
}
