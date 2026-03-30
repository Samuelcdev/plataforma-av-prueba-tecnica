<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Application\DTO\Operative\GetOperativesDto;
use App\Domain\Entities\UserEntity;
use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\Exceptions\UnauthorizedDomainException;
use App\Domain\Repositories\OperativeRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Auth\SanctumAuthProviderInterface;

final class OperativeService
{
    private const ADMIN_ROLE_ID = 1;

    public function __construct(
        private OperativeRepositoryInterface $operatives,
        private UserRepositoryInterface $users,
        private SanctumAuthProviderInterface $sanctumAuth,
    ) {
    }

    /**
     * @return array{operatives: array, total: int}
     */
    public function get(GetOperativesDto $dto): array
    {
        $this->assertAdminRole();

        $filters = [
            'search' => $dto->getSearch(),
            'page' => $dto->getPage(),
            'total' => $dto->getTotal(),
            'is_active' => $dto->getIsActive(),
        ];

        return [
            'operatives' => $this->operatives->all($filters),
            'total' => $this->operatives->count($filters),
        ];
    }

    private function assertAdminRole(): UserEntity
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

        if ($user->getRoleId() !== self::ADMIN_ROLE_ID) {
            throw UnauthorizedDomainException::accessDenied('Only admin users can perform this action.');
        }

        return $user;
    }
}
