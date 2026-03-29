<?php

declare(strict_types=1);

namespace App\Presentation\Http\Resources;

use App\Domain\Entities\UserEntity;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property UserEntity $resource
 */
final class UserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $this->resource;

        return [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'role_id' => $user->getRoleId(),
            'is_active' => $user->getIsActive(),
            'last_login_at' => $user->getLastLoginAt()?->format(DATE_ATOM),
            'created_at' => $user->getCreatedAt()?->format(DATE_ATOM),
            'updated_at' => $user->getUpdatedAt()?->format(DATE_ATOM),
            'deleted_at' => $user->getDeletedAt()?->format(DATE_ATOM),
        ];
    }
}
