<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth;

use App\Domain\Exceptions\UnauthorizedDomainException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

final class SanctumAuthProvider implements SanctumAuthProviderInterface
{
    public function currentUserId(): ?string
    {
        $user = Auth::guard('sanctum')->user();

        return $user?->getAuthIdentifier() !== null
            ? (string) $user->getAuthIdentifier()
            : null;
    }

    public function createToken(string $userId, string $deviceName): string
    {
        $user = User::query()->find($userId);

        if ($user === null) {
            throw UnauthorizedDomainException::accessDenied('Cannot create token for unauthenticated user.');
        }

        return $user->createToken($deviceName)->plainTextToken;
    }

    public function revokeCurrentAccessToken(): void
    {
        $user = Auth::guard('sanctum')->user();

        if ($user === null || $user->currentAccessToken() === null) {
            return;
        }

        $user->currentAccessToken()->delete();
    }
}
