<?php

declare(strict_types=1);

namespace App\Application\DTO\Auth;

final class CurrentUserDto
{
    public function __construct(private ?string $bearerToken = null)
    {
    }

    public function getBearerToken(): ?string
    {
        return $this->bearerToken;
    }
}
