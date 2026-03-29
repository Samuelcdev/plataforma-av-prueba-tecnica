<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\UserEntity;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Persistence\Mapper\UserMapper;
use App\Infrastructure\Persistence\Models\User;
use DateTimeImmutable;

final class EloquentUserRepository implements UserRepositoryInterface
{
    public function __construct(private UserMapper $mapper)
    {
    }

    public function all(): array
    {
        return $this->mapper->toCollectionEntity(
            User::query()->orderBy('created_at')->get()
        );
    }

    public function findById(string $id): ?UserEntity
    {
        $user = User::query()->find($id);

        return $user ? $this->mapper->toEntity($user) : null;
    }

    public function findByUsername(string $username): ?UserEntity
    {
        $user = User::query()->where('username', $username)->first();

        return $user ? $this->mapper->toEntity($user) : null;
    }

    public function findByUsernameIncludingDeleted(string $username): ?UserEntity
    {
        $user = User::query()->withTrashed()->where('username', $username)->first();

        return $user ? $this->mapper->toEntity($user) : null;
    }

    public function save(UserEntity $userEntity): UserEntity
    {
        $user = User::query()->find($userEntity->getId()) ?? new User();

        $user->id = $userEntity->getId();
        $user->username = $userEntity->getUsername();
        $user->password = $userEntity->getPassword();
        $user->role_id = $userEntity->getRoleId();
        $user->is_active = $userEntity->getIsActive();
        $user->last_login_at = $this->toDatabaseDateTime($userEntity->getLastLoginAt());

        if ($userEntity->getDeletedAt() !== null) {
            $user->deleted_at = $this->toDatabaseDateTime($userEntity->getDeletedAt());
        }

        $user->save();

        return $this->mapper->toEntity($user->fresh() ?? $user);
    }

    public function deleteById(string $id): bool
    {
        $user = User::query()->find($id);

        return $user ? (bool) $user->delete() : false;
    }

    private function toDatabaseDateTime(?DateTimeImmutable $value): ?string
    {
        return $value?->format('Y-m-d H:i:s');
    }
}
