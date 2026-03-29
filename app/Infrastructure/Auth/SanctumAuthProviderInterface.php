<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth;

interface SanctumAuthProviderInterface
{
    public function currentUserId(): ?string;

    public function createToken(string $userId, string $deviceName): string;

    public function revokeCurrentAccessToken(): void;
}
