<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Application\DTO\Item\GetItemsDto;
use App\Domain\Entities\UserEntity;
use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\Exceptions\UnauthorizedDomainException;
use App\Domain\Repositories\ItemRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Auth\SanctumAuthProviderInterface;

final class ItemService
{
    private const ADMIN_ROLE_ID = 1;
    private const HOTEL_ROLE_ID = 2;

    public function __construct(
        private ItemRepositoryInterface $items,
        private UserRepositoryInterface $users,
        private SanctumAuthProviderInterface $sanctumAuth,
    ) {
    }

    /**
     * @return array{items: array, total: int}
     */
    public function get(GetItemsDto $dto): array
    {
        $this->assertAllowedRole();

        $filters = [
            'search' => $dto->getSearch(),
            'page' => $dto->getPage(),
            'total' => $dto->getTotal(),
            'is_active' => $dto->getIsActive(),
        ];

        return [
            'items' => $this->items->all($filters),
            'total' => $this->items->count($filters),
        ];
    }

    private function assertAllowedRole(): UserEntity
    {
        $currentUserId = $this->sanctumAuth->currentUserId();

        if ($currentUserId === null) {
            throw UnauthorizedDomainException::accessDenied();
        }

        $user = $this->users->findById($currentUserId);

        if ($user === null) {
            throw EntityNotFoundException::for('User', $currentUserId);
        }

        if (! $user->getIsActive()) {
            throw UnauthorizedDomainException::accessDenied('User account is inactive.');
        }

        if (! in_array($user->getRoleId(), [self::ADMIN_ROLE_ID, self::HOTEL_ROLE_ID], true)) {
            throw UnauthorizedDomainException::accessDenied('User role is not allowed for this action.');
        }

        return $user;
    }
}
