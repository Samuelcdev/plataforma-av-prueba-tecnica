<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Application\DTO\Auth\AuthPayloadDto;
use App\Application\DTO\Auth\CurrentUserDto;
use App\Application\DTO\Auth\LoginDto;
use App\Domain\Entities\UserEntity;
use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\Exceptions\InvalidCredentialsException;
use App\Domain\Exceptions\UnauthorizedDomainException;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Services\AuthServiceInterface;
use App\Infrastructure\Auth\SanctumAuthProviderInterface;
use DateTimeImmutable;
use Illuminate\Support\Facades\Hash;

final class AuthService implements AuthServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $users,
        private SanctumAuthProviderInterface $sanctumAuth,
    ) {
    }

    public function login(LoginDto $dto): AuthPayloadDto
    {
        $user = $this->users->findByUsername($dto->getUsername());

        if ($user === null) {
            throw InvalidCredentialsException::default();
        }

        if (! Hash::check($dto->getPassword(), $user->getPassword())) {
            throw InvalidCredentialsException::default();
        }

        if (! $user->getIsActive()) {
            throw UnauthorizedDomainException::accessDenied('User account is inactive.');
        }

        $user->setLastLoginAt(new DateTimeImmutable());
        $savedUser = $this->users->save($user);

        $token = $this->sanctumAuth->createToken($savedUser->getId(), $dto->getDeviceName());

        return new AuthPayloadDto($savedUser, $token, 'Bearer');
    }

    public function logout(CurrentUserDto $dto): void
    {
        if ($this->sanctumAuth->currentUserId() === null) {
            throw UnauthorizedDomainException::accessDenied('Unauthenticated.');
        }

        $this->sanctumAuth->revokeCurrentAccessToken();
    }

    public function me(CurrentUserDto $dto): UserEntity
    {
        $userId = $this->sanctumAuth->currentUserId();

        if ($userId === null) {
            throw UnauthorizedDomainException::accessDenied('Unauthenticated.');
        }

        $user = $this->users->findById($userId);

        if ($user === null) {
            throw EntityNotFoundException::for('User', $userId);
        }

        return $user;
    }
}
