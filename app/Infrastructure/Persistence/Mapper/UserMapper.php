<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Mapper;

use App\Domain\Entities\UserEntity;
use App\Infrastructure\Persistence\Models\User;
use DateTimeImmutable;
use DateTimeInterface;
use Illuminate\Support\Collection;

final class UserMapper
{
    public function toEntity(User $user): UserEntity
    {
        return new UserEntity(
            (string) $user->id,
            (string) $user->username,
            (string) $user->password,
            (int) $user->role_id,
            (bool) $user->is_active,
            $this->toImmutable($user->last_login_at),
            $this->toImmutable($user->created_at),
            $this->toImmutable($user->updated_at),
            $this->toImmutable($user->deleted_at),
        );
    }

    /**
     * @return array<UserEntity>
     */
    public function toCollectionEntity(Collection|array $items): array
    {
        $models = $items instanceof Collection ? $items->all() : $items;

        return array_map(
            fn (mixed $item): UserEntity => $this->toEntity($item),
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
