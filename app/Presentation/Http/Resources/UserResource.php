<?php

declare(strict_types=1);

namespace App\Presentation\Http\Resources;

use App\Domain\Entities\UserEntity;

/**
 * @property UserEntity $resource
 */
final class UserResource
{
    /**
     * @return array<string, mixed>
     */
    public static function toArray(UserEntity $user): array
    {
        return [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'is_active' => $user->getIsActive(),
            'last_login_at' => $user->getLastLoginAt()?->format(DATE_ATOM),
            'created_at' => $user->getCreatedAt()?->format(DATE_ATOM),
            'updated_at' => $user->getUpdatedAt()?->format(DATE_ATOM),
            'deleted_at' => $user->getDeletedAt()?->format(DATE_ATOM),
        ];
    }
}
