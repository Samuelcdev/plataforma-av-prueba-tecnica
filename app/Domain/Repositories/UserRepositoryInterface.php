<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\UserEntity;

interface UserRepositoryInterface
{
    /**
     * @return list<UserEntity>
     */
    public function all(): array;
    public function findById(string $id): ?UserEntity;
    public function findByUsername(string $username): ?UserEntity;
    public function findByUsernameIncludingDeleted(string $username): ?UserEntity;
    public function save(UserEntity $user): UserEntity;
    public function deleteById(string $id): bool;
}
