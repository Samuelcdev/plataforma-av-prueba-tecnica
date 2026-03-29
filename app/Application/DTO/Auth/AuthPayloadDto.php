<?php

declare(strict_types=1);

namespace App\Application\DTO\Auth;

use App\Domain\Entities\UserEntity;

final class AuthPayloadDto
{
    public function __construct(
        private UserEntity $user,
        private string $token,
        private string $tokenType = 'Bearer',
    ) {
    }

    public function getUser(): UserEntity
    {
        return $this->user;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }
}
