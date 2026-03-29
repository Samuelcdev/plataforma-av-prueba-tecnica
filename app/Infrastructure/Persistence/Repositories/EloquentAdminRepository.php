<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\AdminEntity;
use App\Domain\Repositories\AdminRepositoryInterface;
use App\Infrastructure\Persistence\Mapper\AdminMapper;
use App\Infrastructure\Persistence\Models\Admin;

final class EloquentAdminRepository implements AdminRepositoryInterface
{
    public function __construct(private AdminMapper $mapper)
    {
    }

    public function all(): array
    {
        return $this->mapper->toCollectionEntity(
            Admin::query()->orderBy('name')->get()
        );
    }

    public function findById(string $id): ?AdminEntity
    {
        $admin = Admin::query()->find($id);

        return $admin ? $this->mapper->toEntity($admin) : null;
    }

    public function findByUserId(string $userId): ?AdminEntity
    {
        $admin = Admin::query()->where('user_id', $userId)->first();

        return $admin ? $this->mapper->toEntity($admin) : null;
    }

    public function save(AdminEntity $adminEntity): AdminEntity
    {
        $admin = Admin::query()->find($adminEntity->getId()) ?? new Admin();

        $admin->id = $adminEntity->getId();
        $admin->user_id = $adminEntity->getUserId();
        $admin->document_type = $adminEntity->getDocumentType();
        $admin->document = $adminEntity->getDocument();
        $admin->name = $adminEntity->getName();
        $admin->email = $adminEntity->getEmail();
        $admin->phone = $adminEntity->getPhone();

        $admin->save();

        return $this->mapper->toEntity($admin->fresh() ?? $admin);
    }

    public function deleteById(string $id): bool
    {
        $admin = Admin::query()->find($id);

        return $admin ? (bool) $admin->delete() : false;
    }
}
