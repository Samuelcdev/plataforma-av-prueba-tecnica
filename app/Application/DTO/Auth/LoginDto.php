<?php

declare(strict_types=1);

namespace App\Application\DTO\Auth;

final class LoginDto
{
    public function __construct(
        private string $username,
        private string $password,
        private string $deviceName = 'api-token',
    ) {
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getDeviceName(): string
    {
        return $this->deviceName;
    }
}
